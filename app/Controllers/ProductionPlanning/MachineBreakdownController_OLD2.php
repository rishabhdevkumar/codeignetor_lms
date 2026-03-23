<?php

namespace App\Controllers\ProductionPlanning;

use App\Models\Customer\CustomerModel;
use CodeIgniter\Controller;
use App\Models\ProductionPlanning\PlanningProductionModel;
use App\Models\ProductionPlanning\PpCalendarApprovalModel;
use App\Models\ProductionPlanning\PpMachineAvailabilityModel;
use App\Models\Machine\MachineModel;
use App\Models\Customer\CustomerTransitModel;
use App\Models\Material\MaterialModel;
use App\Models\OrderGeneration\IndentModel;
use App\Models\IndentAllotment\IndentAllotmentModel;

class MachineBreakdownController extends Controller
{
    protected $planningModel;
    protected $approvalModel;
    protected $availabilityModel;
    protected $machineMasterModel;
    protected $indentAllotment;
    protected $materialModel;
    protected $transitMaster;
    protected $indentModel;
    protected $ppCustomerMaster;
    protected $db;

    public function __construct()
    {
        $this->planningModel = new PlanningProductionModel();
        $this->approvalModel = new PpCalendarApprovalModel();
        $this->availabilityModel = new PpMachineAvailabilityModel();
        $this->machineMasterModel = new MachineModel();
        $this->indentAllotment = new IndentAllotmentModel();
        $this->materialModel = new MaterialModel();
        $this->transitMaster = new CustomerTransitModel();
        $this->indentModel = new IndentModel();
        $this->ppCustomerMaster = new CustomerModel();
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

                $machine_pin_code = $machine['PIN_CODE'];

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
                    ->where('TO_DATE_TIME >', $bdFrom->format('Y-m-d H:i:s'))
                    ->orderBy('FROM_DATE_TIME', 'ASC')
                    ->findAll();

                $shiftSeconds = $bdSeconds;
                $previousEnd = null;

                foreach ($plannings as $plan) {

                    $planFrom = new \DateTime($plan['FROM_DATE_TIME']);
                    $planTo = new \DateTime($plan['TO_DATE_TIME']);

                    $durationSeconds = $planTo->getTimestamp() - $planFrom->getTimestamp();

                    // If plan starts before breakdown and overlaps
                    if ($planTo > $bdFrom && $planFrom < $bdTo) {

                        // Push end by breakdown duration
                        $planTo->modify("+{$shiftSeconds} seconds");

                        // If not yet started fully, shift start too
                        if ($planFrom >= $bdFrom) {
                            $planFrom->modify("+{$shiftSeconds} seconds");
                        }
                    }
                    // Plans fully after breakdown
                    elseif ($planFrom >= $bdFrom) {

                        $planFrom->modify("+{$shiftSeconds} seconds");
                        $planTo->modify("+{$shiftSeconds} seconds");
                    }

                    // Maintain continuity (important!)
                    if ($previousEnd && $planFrom < $previousEnd) {
                        $planFrom = clone $previousEnd;
                        $planTo = (clone $planFrom)->modify("+{$durationSeconds} seconds");
                    }

                    $this->planningModel->update($plan['PP_ID'], [
                        'FROM_DATE_TIME' => $planFrom->format('Y-m-d H:i:s'),
                        'TO_DATE_TIME' => $planTo->format('Y-m-d H:i:s')
                    ]);

                    $updatedPlanning = $plan;
                    $updatedPlanning['FROM_DATE_TIME'] = $planFrom->format('Y-m-d H:i:s');
                    $updatedPlanning['TO_DATE_TIME'] = $planTo->format('Y-m-d H:i:s');
                    $updatedPlanning['MACHINE_PINCODE'] = $machine_pin_code;

                    $this->recalculateIndentAllotments($plan['PP_ID'], $updatedPlanning);

                    $previousEnd = clone $planTo;
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
            ->orderBy('INDENT_LINE_ITEM', 'ASC')
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

            $durationSeconds = $oldTo->getTimestamp() - $oldFrom->getTimestamp();

            if ($durationSeconds <= 0) {
                $durationSeconds = $fallbackSeconds;
            }

            $fromDate = clone $currentStart;

            $toDate = clone $fromDate;
            $toDate->modify("+{$durationSeconds} seconds");

            $indent = $this->indentModel
                ->select('bill_to_code')
                ->where('in_no', $allotment['INDENT_NO'])
                ->first();

            // $customerPincode = $this->ppCustomerMaster
            //     ->select('PIN_CODE')
            //     ->where('CUSTOMER_CODE', $indent['bill_to_code'])
            //     ->first();



            $material = $this->materialModel
                ->select('PACKAGING_TIME')
                ->where('FINISH_MATERIAL_CODE', $allotment['FINISH_MATERIAL_CODE'])
                ->first();

            $packagingDays = (int) ($material['PACKAGING_TIME'] ?? 0);

            $finishingDate = clone $toDate;
            if ($packagingDays > 0) {
                $finishingDate->add(new \DateInterval("P{$packagingDays}D"));
            }

            // $transit = $this->transitMaster
            //     ->select('TRANSIT_TIME')
            //     ->where('FROM_PINCODE', $newPlanning['MACHINE_PINCODE'] ?? null)
            //     ->where('TO_PINCODE', $customerPincode ?? null)
            //     ->first();

            $transitHours = (int) ($allotment['TRANSIT_TIME'] ?? 0);

            $doorStepDate = clone $finishingDate;

            if ($transitHours > 0) {
                $doorStepDate->add(new \DateInterval("P{$transitHours}D"));
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
