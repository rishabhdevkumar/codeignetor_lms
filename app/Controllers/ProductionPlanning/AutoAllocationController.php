<?php

namespace App\Controllers\ProductionPlanning;

use App\Controllers\BaseController;
use App\Models\ProductionPlanning\PlanningProductionModel;
use App\Models\OrderGeneration\IndentAllotmentModel;
use App\Models\MasterModels\TransitMaster;
use App\Models\Material_Model;
use CodeIgniter\Controller;

class AutoAllocationController extends BaseController
{
    protected $planningModel;
    protected $indentModel;
    protected $materialModel;
    protected $transitMaster;
    protected $db;

    public function __construct()
    {
        $this->planningModel = new PlanningProductionModel();
        $this->indentModel = new IndentAllotmentModel();
        $this->transitMaster = new TransitMaster();
        $this->materialModel = new Material_Model();
        $this->db = \Config\Database::connect();
    }

    public function run()
    {
        $tomorrowStart = date('Y-m-d 00:00:00', strtotime('+1 day'));
        $tomorrowEnd = date('Y-m-d 23:59:59', strtotime('+1 day'));

        $planningSlots = $this->planningModel
            ->where('FROM_DATE_TIME >=', $tomorrowStart)
            ->where('FROM_DATE_TIME <=', $tomorrowEnd)
            ->where('REALLOCATION_STATUS', 0)
            ->findAll();

        if (empty($planningSlots)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No planning slots found for tomorrow'
            ]);
        }

        $this->db->transBegin();

        try {

            foreach ($planningSlots as $planning) {

                // STEP 2: Pick Eligible Indents
                $indents = $this->indentModel
                    ->where('MR_MATERIAL_CODE', $planning['SAP_MR_FG_CODE'])
                    ->where('FROM_DATE >', $planning['FROM_DATE_TIME'])
                    ->where('PLANNING_CAL_ID !=', $planning['PP_ID'])
                    ->whereIn('CUSTOMER_TYPE', ['NKC', 'KC1', 'KC2'])
                    ->orderBy('FROM_DATE', 'ASC')
                    ->findAll();

                foreach ($indents as $indent) {

                    // Capacity check
                    if ($indent['QUANTITY'] > $planning['BALANCE_QTY']) {
                        continue;
                    }

                    $oldPlanningId = $indent['PLANNING_CAL_ID'];

                    // STEP 3A: Move indent (NO date update here)
                    $this->indentModel->update($indent['PP_ID'], [
                        'PLANNING_CAL_ID' => $planning['PP_ID'],
                        'OLD_FROM_DATE' => $indent['FROM_DATE'],
                        'OLD_TO_DATE' => $indent['TO_DATE'],
                    ]);

                    /** STEP 4: Reverse OLD planning qty */
                    $oldPlanning = $this->planningModel
                        ->where('PP_ID', $oldPlanningId)
                        ->first();

                    if ($oldPlanning) {

                        $reverse = [
                            'UTILISED_QTY' => $oldPlanning['UTILISED_QTY'] - $indent['QUANTITY'],
                            'BALANCE_QTY' => $oldPlanning['BALANCE_QTY'] + $indent['QUANTITY'],

                            'NKC_UTILISED_QTY_MT' => $oldPlanning['NKC_UTILISED_QTY_MT'],
                            'NKC_BALANCE_QTY_MT' => $oldPlanning['NKC_BALANCE_QTY_MT'],
                            'KC1_UTILISED_QTY_MT' => $oldPlanning['KC1_UTILISED_QTY_MT'],
                            'KC1_BALANCE_QTY_MT' => $oldPlanning['KC1_BALANCE_QTY_MT'],
                            'KC2_UTILISED_QTY_MT' => $oldPlanning['KC2_UTILISED_QTY_MT'],
                            'KC2_BALANCE_QTY_MT' => $oldPlanning['KC2_BALANCE_QTY_MT'],
                        ];

                        if ($indent['CUSTOMER_TYPE'] === 'NKC') {
                            $reverse['NKC_UTILISED_QTY_MT'] -= $indent['QUANTITY'];
                            $reverse['NKC_BALANCE_QTY_MT'] += $indent['QUANTITY'];
                        }

                        if ($indent['CUSTOMER_TYPE'] === 'KC1') {
                            $reverse['KC1_UTILISED_QTY_MT'] -= $indent['QUANTITY'];
                            $reverse['KC1_BALANCE_QTY_MT'] += $indent['QUANTITY'];
                        }

                        if ($indent['CUSTOMER_TYPE'] === 'KC2') {
                            $reverse['KC2_UTILISED_QTY_MT'] -= $indent['QUANTITY'];
                            $reverse['KC2_BALANCE_QTY_MT'] += $indent['QUANTITY'];
                        }

                        $this->planningModel->update($oldPlanning['PP_ID'], $reverse);
                    }

                    // STEP 5: Apply qty to NEW planning 
                    $apply = [
                        'UTILISED_QTY' => $planning['UTILISED_QTY'] + $indent['QUANTITY'],
                        'BALANCE_QTY' => $planning['BALANCE_QTY'] - $indent['QUANTITY'],

                    ];

                    $this->planningModel->update($planning['PP_ID'], $apply);

                    // keep in-memory planning updated
                    $planning = array_merge($planning, $apply);
                }

                // STEP 6: Recalculate indent dates SEQUENTIALLY 
                $this->recalculateIndentAllotments(
                    $planning['PP_ID'],
                    $planning
                );

                // STEP 7: Mark planning reallocated 
                $this->planningModel->update($planning['PP_ID'], [
                    'REALLOCATION_STATUS' => 1
                ]);
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Reallocation completed successfully'
            ]);
        } catch (\Throwable $e) {

            $this->db->transRollback();

            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    private function recalculateIndentAllotments(int $planningCalId, array $planning)
    {
        $allotments = $this->indentModel
            ->where('PLANNING_CAL_ID', $planningCalId)
            ->orderBy('PP_ID', 'ASC') // deterministic order
            ->findAll();

        if (empty($allotments)) {
            return;
        }

        // 1️ First row = EXISTING anchor (DO NOT TOUCH)
        $first = array_shift($allotments);

        // Anchor from existing TO_DATE
        $currentStart = new \DateTime($first['TO_DATE']);

        // 2️ Process remaining rows
        foreach ($allotments as $allotment) {

            // Skip existing indents (extra safety)
            if (empty($allotment['OLD_FROM_DATE'])) {
                continue;
            }

            // original duration
            $oldFrom = new \DateTime($allotment['OLD_FROM_DATE']);
            $oldTo = new \DateTime($allotment['OLD_TO_DATE']);

            $durationSeconds = max(
                $oldTo->getTimestamp() - $oldFrom->getTimestamp(),
                60
            );

            // new FROM / TO
            $fromDate = clone $currentStart;
            $toDate = (clone $fromDate)->modify("+{$durationSeconds} seconds");

            // packaging time
            $material = $this->materialModel
                ->select('PACKAGING_TIME')
                ->where('FINISH_MATERIAL_CODE', $allotment['FINISH_MATERIAL_CODE'])
                ->first();

            $packagingDays = (int) ($material['PACKAGING_TIME'] ?? 0);

            $finishingDate = clone $toDate;
            if ($packagingDays > 0) {
                $finishingDate->add(new \DateInterval("P{$packagingDays}D"));
            }

            // transit time
            $transit = $this->transitMaster
                ->select('TRANSIT_TIME')
                ->where('FROM_PINCODE', $planning['MACHINE_PINCODE'] ?? null)
                ->where('TO_PINCODE', $allotment['CUSTOMER_PIN_CODE'] ?? null)
                ->first();

            $transitDays = (int) ($transit['TRANSIT_TIME'] ?? 0);

            $doorStepDate = clone $finishingDate;
            if ($transitDays > 0) {
                $doorStepDate->add(new \DateInterval("P{$transitDays}D"));
            }

            // update ONLY newly moved indent
            $this->indentModel->update($allotment['PP_ID'], [
                'FROM_DATE' => $fromDate->format('Y-m-d H:i:s'),
                'TO_DATE' => $toDate->format('Y-m-d H:i:s'),
                'FINISHING_DATE' => $finishingDate->format('Y-m-d H:i:s'),
                'DOOR_STEP_DEL_DATE' => $doorStepDate->format('Y-m-d H:i:s'),
                'MODIFICATION_FLAG' => 1
            ]);

            // move anchor forward
            $currentStart = clone $toDate;
        }
    }



}
