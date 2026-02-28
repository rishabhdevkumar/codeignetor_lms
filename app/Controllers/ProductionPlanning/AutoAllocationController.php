<?php

namespace App\Controllers\ProductionPlanning;

use App\Controllers\BaseController;
use App\Models\ProductionPlanning\PlanningProductionModel;
use App\Models\IndentAllotment\IndentAllotmentModel;
use App\Models\MasterModels\TransitMaster;
use App\Models\ProductionPlanning\PlanningProductionHistoryModel;
use App\Models\Material_Model;
use CodeIgniter\Controller;

class AutoAllocationController extends BaseController
{
    protected $planningModel;
    protected $indentModel;
    protected $transitMaster;
    protected $planningCalhistoryModel;
    protected $materialModel;
    protected $db;

    public function __construct()
    {
        $this->planningModel = new PlanningProductionModel();
        $this->indentModel = new IndentAllotmentModel();
        $this->transitMaster = new TransitMaster();
        $this->planningCalhistoryModel = new PlanningProductionHistoryModel();
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

            $this->deleteUnUtilizedPlan();
            $this->deletePartialUtilizedPlan();

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

        // First row = EXISTING anchor (DO NOT TOUCH)
        $first = array_shift($allotments);

        // Anchor from existing TO_DATE
        $currentStart = new \DateTime($first['TO_DATE']);

        // Process remaining rows
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
            $packagingDays = (int) ($allotment['PACKAGING_TIME'] ?? 0);

            $finishingDate = clone $toDate;
            if ($packagingDays > 0) {
                $finishingDate->add(new \DateInterval("P{$packagingDays}D"));
            }

            // transit time
            $transitDays = (int) ($allotment['TRANSIT_TIME'] ?? 0);

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

                'OLD_FROM_DATE' => $allotment['FROM_DATE'],
                'OLD_TO_DATE' => $allotment['TO_DATE'],
                'OLD_FINISHING_DATE' => $allotment['FINISHING_DATE'],
                'OLD_DOOR_STEP_DEL_DATE' => $allotment['DOOR_STEP_DEL_DATE'],

                'MODIFICATION_FLAG' => 1
            ]);

            // move anchor forward
            $currentStart = clone $toDate;
        }
    }

    private function deleteUnUtilizedPlan()
    {
        $tomorrowStart = date('Y-m-d 00:00:00', strtotime('+1 day'));
        $tomorrowEnd = date('Y-m-d 23:59:59', strtotime('+1 day'));

        $unutilizedplanningSlots = $this->planningModel
            ->where('FROM_DATE_TIME >=', $tomorrowStart)
            ->where('FROM_DATE_TIME <=', $tomorrowEnd)
            ->where('REALLOCATION_STATUS', 1)
            ->where('UTILISED_QTY =', 0.00)
            ->findAll();

        $this->db->transBegin();

        try {

            foreach ($unutilizedplanningSlots as $slot) {

                $oldPlanning = $this->planningModel
                    ->where('PP_ID', $slot['PP_ID'])
                    ->first();

                $oldPlanning['PLANNING_CAL_ID'] = $oldPlanning['PP_ID'];
                $oldPlanning['REMARKS'] = 'Plan Not Utilized';
                $this->planningCalhistoryModel->insert($oldPlanning);

                $totalElapsedSeconds = 0;

                $start = strtotime($slot['FROM_DATE_TIME']);
                $end   = strtotime($slot['TO_DATE_TIME']);

                $totalElapsedSeconds += ($end - $start);

                $remainingPlans = $this->planningModel
                    ->where('FROM_DATE_TIME >=', $tomorrowEnd)
                    ->where('MACHINE', $slot['MACHINE'])
                    ->where('REALLOCATION_STATUS', 0)
                    ->findAll();

                foreach ($remainingPlans as $plan) {

                    $newFrom = date(
                        'Y-m-d H:i:s',
                        strtotime($plan['FROM_DATE_TIME']) - $totalElapsedSeconds
                    );

                    $newTo = date(
                        'Y-m-d H:i:s',
                        strtotime($plan['TO_DATE_TIME']) - $totalElapsedSeconds
                    );

                    $remainingPlans['FROM_DATE_TIME'] = $newFrom;
                    $remainingPlans['TO_DATE_TIME'] = $newTo;

                    $this->planningModel->update($plan['PP_ID'], [
                        'FROM_DATE_TIME' => $newFrom,
                        'TO_DATE_TIME'   => $newTo
                    ]);

                    // Recalculate indent dates SEQUENTIALLY 
                    $this->recalculateIndentAfterDeletion(
                        $plan['PP_ID'],
                        $remainingPlans
                    );
                }
            }

            $this->db->transCommit();
        } catch (\Throwable $e) {

            $this->db->transRollback();

            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function deletePartialUtilizedPlan()
    {
        $tomorrowStart = date('Y-m-d 00:00:00', strtotime('+1 day'));
        $tomorrowEnd = date('Y-m-d 23:59:59', strtotime('+1 day'));

        $partialutilizedplanningSlots = $this->planningModel
            ->where('FROM_DATE_TIME >=', $tomorrowStart)
            ->where('FROM_DATE_TIME <=', $tomorrowEnd)
            ->where('REALLOCATION_STATUS', 1)
            ->where('BALANCE_QTY >', 0.00)
            ->findAll();

        $this->db->transBegin();

        try {

            foreach ($partialutilizedplanningSlots as $slot) {

                $oldPlanning = $this->planningModel
                    ->where('PP_ID', $slot['PP_ID'])
                    ->first();

                $oldPlanning['PLANNING_CAL_ID'] = $oldPlanning['PP_ID'];
                $oldPlanning['REMARKS'] = 'Plan Partial Utilized';
                $this->planningCalhistoryModel->insert($oldPlanning);

                $totalElapsedSeconds = 0;

                $start = strtotime($slot['FROM_DATE_TIME']);
                $end   = strtotime($slot['TO_DATE_TIME']);

                $totalElapsedSeconds += ($end - $start);

                // Logic to Short Close Planning Date on the basis of Utilized Qty
                $plannedQty  = $slot['QTY_MT'];
                $utilizedQty = $slot['BALANCE_QTY'];

                $actualUsedSeconds = 0;

                if ($plannedQty > 0) {
                    $actualUsedSeconds = ($utilizedQty / $plannedQty) * $totalElapsedSeconds;
                }

                $shortCloseSeconds = $totalElapsedSeconds - $actualUsedSeconds;

                $this->planningModel->update($slot['PP_ID'], [
                    'TO_DATE_TIME' => date('Y-m-d H:i:s', strtotime($slot['TO_DATE_TIME']) - $shortCloseSeconds)
                ]);

                $remainingPlans = $this->planningModel
                    ->where('FROM_DATE_TIME >=', $tomorrowEnd)
                    ->where('MACHINE', $slot['MACHINE'])
                    ->where('REALLOCATION_STATUS', 0)
                    ->findAll();

                foreach ($remainingPlans as $plan) {

                    $newFrom = date(
                        'Y-m-d H:i:s',
                        strtotime($plan['FROM_DATE_TIME']) - $shortCloseSeconds
                    );

                    $newTo = date(
                        'Y-m-d H:i:s',
                        strtotime($plan['TO_DATE_TIME']) - $shortCloseSeconds
                    );

                    $remainingPlans['FROM_DATE_TIME'] = $newFrom;
                    $remainingPlans['TO_DATE_TIME'] = $newTo;

                    $this->planningModel->update($plan['PP_ID'], [
                        'FROM_DATE_TIME' => $newFrom,
                        'TO_DATE_TIME'   => $newTo
                    ]);

                    // Recalculate indent dates SEQUENTIALLY 
                    $this->recalculateIndentAfterDeletion(
                        $plan['PP_ID'],
                        $remainingPlans
                    );
                }
            }

            $this->db->transCommit();
        } catch (\Throwable $e) {

            $this->db->transRollback();

            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function recalculateIndentAfterDeletion(int $planningCalId, array $newPlanning)
    {

        $allotments = $this->indentModel
            ->where('PLANNING_CAL_ID', $planningCalId)
            ->orderBy('PP_ID', 'ASC')
            ->findAll();

        if (empty($allotments)) {
            return;
        }

        $planningFrom = new \DateTime($newPlanning['FROM_DATE_TIME']);
        $planningTo   = new \DateTime($newPlanning['TO_DATE_TIME']);

        $planTotalSeconds = $planningTo->getTimestamp() - $planningFrom->getTimestamp();

        $currentStart = clone $planningFrom;
        $consumedSeconds = 0;

        foreach ($allotments as $index => $allotment) {

            $oldFrom = new \DateTime($allotment['FROM_DATE']);
            $oldTo = new \DateTime($allotment['TO_DATE']);

            $durationSeconds = max(
                $oldTo->getTimestamp() - $oldFrom->getTimestamp(),
                60
            );

            // Safety: do not exceed planning window
            if (($consumedSeconds + $durationSeconds) > $planTotalSeconds) {
                $durationSeconds = $planTotalSeconds - $consumedSeconds;
            }

            $fromDate = clone $currentStart;

            $toDate = clone $fromDate;
            $toDate->modify("+{$durationSeconds} seconds");

            $packagingDays = (int) ($allotment['PACKAGING_TIME'] ?? 0);

            $finishingDate = clone $toDate;
            if ($packagingDays > 0) {
                $finishingDate->add(new \DateInterval("P{$packagingDays}D"));
            }

            $transitDays = (int) ($allotment['TRANSIT_TIME'] ?? 0);

            $doorStepDate = clone $finishingDate;
            if ($transitDays > 0) {
                $doorStepDate->add(new \DateInterval("P{$transitDays}D"));
            }


            $this->indentModel->update($allotment['PP_ID'], [
                'FROM_DATE' => $fromDate->format('Y-m-d H:i:s'),
                'TO_DATE' => $toDate->format('Y-m-d H:i:s'),
                'FINISHING_DATE' => $finishingDate->format('Y-m-d H:i:s'),
                'DOOR_STEP_DEL_DATE' => $doorStepDate->format('Y-m-d H:i:s'),


                'OLD_FROM_DATE' => $allotment['FROM_DATE'],
                'OLD_TO_DATE' => $allotment['TO_DATE'],
                'OLD_FINISHING_DATE' => $allotment['FINISHING_DATE'],
                'OLD_DOOR_STEP_DEL_DATE' => $allotment['DOOR_STEP_DEL_DATE'],

                'MODIFICATION_FLAG' => 1
            ]);

            $currentStart = clone $toDate;
            $consumedSeconds += $durationSeconds;
        }
    }
}
