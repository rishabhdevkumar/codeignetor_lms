<?php

namespace App\Controllers\ProductionPlanning;

use CodeIgniter\Controller;
use App\Models\ProductionPlanning\PlanningProductionModel;
use App\Models\ProductionPlanning\PpCalendarApprovalModel;
use App\Models\ProductionPlanning\PpMachineAvailabilityModel;
use App\Models\Machine_Model;
use App\Models\OrderGeneration\IndentAllotmentModel;
use App\Models\MasterModels\TransitMaster;
use App\Models\Material_Model;

class MachineBreakdownController extends Controller
{
    protected $planningModel;
    protected $approvalModel;
    protected $availabilityModel;
    protected $machineMasterModel;
    protected $indentAllotment;
    protected $materialModel;
    protected $transitMaster;
    protected $db;

    public function __construct()
    {
        $this->planningModel = new PlanningProductionModel();
        $this->approvalModel = new PpCalendarApprovalModel();
        $this->availabilityModel = new PpMachineAvailabilityModel();
        $this->machineMasterModel = new Machine_Model();
        $this->indentAllotment = new IndentAllotmentModel();
        $this->transitMaster = new TransitMaster();
        $this->materialModel = new Material_Model();
        $this->db = \Config\Database::connect();
    }

    public function process()
    {
        $breakdowns = $this->availabilityModel
            ->groupStart()
            ->where('PROCESS_DATE_TIME', '0000-00-00 00:00:00')
            ->orWhere('PROCESS_DATE_TIME IS NULL')
            ->groupEnd()
            ->orderBy('FROM_DATE', 'ASC')
            ->findAll();


        if (empty($breakdowns)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'No machine breakdowns to process'
            ]);
        }

        $this->db->transBegin();

        try {

            foreach ($breakdowns as $bd) {

                // Resolve machine internal ID
                $machine = $this->machineMasterModel
                    ->where('MACHINE_TPM_ID', $bd['MACHINE_TPM_ID'])
                    ->first();

                if (!$machine) {
                    continue;
                }

                $machineId = $machine['PP_ID'];

                $bdFrom = new \DateTime($bd['FROM_DATE']);
                $bdTo = new \DateTime($bd['TO_DATE']);
                $bdSeconds = $bdTo->getTimestamp() - $bdFrom->getTimestamp();

                if ($bdSeconds <= 0) {
                    continue;
                }

                // LIVE PLANNING CALENDARS
                $plannings = $this->planningModel
                    ->where('MACHINE', $machineId)
                    // ->where('FROM_DATE_TIME <', $bdTo->format('Y-m-d H:i:s'))
                    ->where('TO_DATE_TIME >', $bdFrom->format('Y-m-d H:i:s'))
                    ->findAll();

                    // echo '<pre>';
                    // print_r($plannings);
                    // echo '</pre>';
                    // exit;

                foreach ($plannings as $plan) {
                    $planFrom = new \DateTime($plan['FROM_DATE_TIME']);
                    $planTo = new \DateTime($plan['TO_DATE_TIME']);

                    $overlapStart = ($planFrom > $bdFrom) ? $planFrom : $bdFrom;
                    $overlapEnd   = ($planTo < $bdTo) ? $planTo : $bdTo;

                    // echo '<pre>';
                    // print_r($overlapEnd);
                    // echo '</pre>';
                    // exit;


                    // if ($overlapEnd <= $overlapStart) {
                    //     continue; // no impact
                    // }

                    $overlapSeconds = $overlapEnd->getTimestamp() - $overlapStart->getTimestamp();

                    // ---- FROM DATE LOGIC ----
                    $newFrom = clone $planFrom;

                    // shift FROM only if planning has not started
                    if ($planFrom >= $bdFrom) {
                        $newFrom->modify("+{$overlapSeconds} seconds");
                    }

                    // ---- TO DATE ALWAYS EXTENDS ----
                    $newTo = clone $planTo;
                    $newTo->modify("+{$overlapSeconds} seconds");

                    $this->planningModel->update($plan['PP_ID'], [
                        'FROM_DATE_TIME' => $newFrom->format('Y-m-d H:i:s'),
                        'TO_DATE_TIME' => $newTo->format('Y-m-d H:i:s')
                    ]);

                    // recalc indents using UPDATED planning window
                    $updatedPlanning = $plan;
                    $updatedPlanning['FROM_DATE_TIME'] = $newFrom->format('Y-m-d H:i:s');
                    $updatedPlanning['TO_DATE_TIME'] = $newTo->format('Y-m-d H:i:s');

                    $this->recalculateIndentAllotments($plan['PP_ID'], $updatedPlanning);
                }


                // PENDING APPROVAL CALENDARS
                $approvals = $this->approvalModel
                    ->where('MACHINE', $machineId)
                    ->where('APPROVAL_STATUS', 'P')
                    ->where('FROM_DATE_TIME <', $bdTo->format('Y-m-d H:i:s'))
                    ->where('TO_DATE_TIME >', $bdFrom->format('Y-m-d H:i:s'))
                    ->findAll();

                foreach ($approvals as $ap) {

                    $newFrom = (new \DateTime($ap['FROM_DATE_TIME']))
                        ->modify("+{$bdSeconds} seconds");

                    $newTo = (new \DateTime($ap['TO_DATE_TIME']))
                        ->modify("+{$bdSeconds} seconds");

                    $this->approvalModel->update($ap['PP_ID'], [
                        'FROM_DATE_TIME' => $newFrom->format('Y-m-d H:i:s'),
                        'TO_DATE_TIME' => $newTo->format('Y-m-d H:i:s')
                    ]);
                }

                // Mark breakdown processed
                $this->availabilityModel->update($bd['PP_ID'], [
                    'PROCESS_DATE_TIME' => date('Y-m-d H:i:s')
                ]);
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Machine breakdown impact processed successfully'
            ]);
        } catch (\Throwable $e) {

            $this->db->transRollback();

            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function recalculateIndentAllotments(int $planningCalId, array $newPlanning)
    {

        $allotments = $this->indentAllotment
            ->where('PLANNING_CAL_ID', $planningCalId)
            ->orderBy('PP_ID', 'ASC')
            ->findAll();

        if (empty($allotments)) {
            return;
        }

        $planningFrom = new \DateTime($newPlanning['FROM_DATE_TIME']);
        $planningTo = new \DateTime($newPlanning['TO_DATE_TIME']);

        $totalSeconds = $planningTo->getTimestamp() - $planningFrom->getTimestamp();
        $indentCount = count($allotments);

        $fallbackSeconds = (int) ($totalSeconds / $indentCount);

        $currentStart = clone $planningFrom;

        foreach ($allotments as $index => $allotment) {

            $oldFrom = new \DateTime($allotment['OLD_FROM_DATE'] ?? $allotment['FROM_DATE']);
            $oldTo = new \DateTime($allotment['OLD_TO_DATE'] ?? $allotment['TO_DATE']);

            // $durationSeconds = $oldTo->getTimestamp() - $oldFrom->getTimestamp();

            $durationSeconds = max(
                $oldTo->getTimestamp() - $oldFrom->getTimestamp(),
                60
            );

            // if ($durationSeconds <= 0) {
            //     $durationSeconds = $fallbackSeconds;
            // }

            $fromDate = clone $currentStart;

            $toDate = clone $fromDate;
            $toDate->modify("+{$durationSeconds} seconds");


            // $material = $this->materialModel
            //     ->select('PACKAGING_TIME')
            //     ->where('FINISH_MATERIAL_CODE', $allotment['FINISH_MATERIAL_CODE'])
            //     ->first();

            $packagingDays = (int) ($allotment['PACKAGING_TIME'] ?? 0);

            $finishingDate = clone $toDate;
            if ($packagingDays > 0) {
                $finishingDate->add(new \DateInterval("P{$packagingDays}D"));
            }

            // $transit = $this->transitMaster
            //     ->select('TRANSIT_TIME')
            //     ->where('FROM_PINCODE', $newPlanning['MACHINE_PINCODE'] ?? null)
            //     ->where('TO_PINCODE', $allotment['CUSTOMER_PIN_CODE'] ?? null)
            //     ->first();

            $transitDays = (int) ($allotment['TRANSIT_TIME'] ?? 0);

            $doorStepDate = clone $finishingDate;
            if ($transitDays > 0) {
                $doorStepDate->add(new \DateInterval("P{$transitDays}D"));
            }


            $this->indentAllotment->update($allotment['PP_ID'], [
                'FROM_DATE' => $fromDate->format('Y-m-d H:i:s'),
                'TO_DATE' => $toDate->format('Y-m-d H:i:s'),
                'FINISHING_DATE' => $finishingDate->format('Y-m-d H:i:s'),
                'DOOR_STEP_DEL_DATE' => $doorStepDate->format('Y-m-d H:i:s'),
                'MODIFICATION_FLAG' => 1
            ]);

            $currentStart = clone $toDate;
        }
    }
}
