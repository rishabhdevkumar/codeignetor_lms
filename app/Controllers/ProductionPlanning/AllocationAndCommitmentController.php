<?php

namespace App\Controllers\ProductionPlanning;

use App\Controllers\BaseController;
use App\Models\MasterModels\FinishStock;
use App\Models\OrderGeneration\IndentModel;
use App\Models\OrderGeneration\IndentDetailsModel;
use App\Models\OrderGeneration\IndentAllotmentModel;
use App\Models\MasterModels\PpCustomerMaster;
use App\Models\MasterModels\TransitMaster;
use App\Models\Material_Model;
use App\Models\Machine_Model;
use App\Models\ProductionPlanning\PlanningProductionModel;
use App\Models\MRMaterial_Model;


class AllocationAndCommitmentController extends BaseController
{
    protected $indentModel;
    protected $indentDetailsModel;
    protected $ppCustomerMaster;
    protected $finishStock;
    protected $materialModel;
    protected $machineModel;
    protected $transitMaster;
    protected $indentAllotment;
    protected $planningProductionModel;
    protected $mrMaterialModel;

    public function __construct()
    {
        $this->indentModel = new IndentModel();
        $this->indentDetailsModel = new IndentDetailsModel();
        $this->ppCustomerMaster = new PpCustomerMaster();
        $this->finishStock = new FinishStock();
        $this->materialModel = new Material_Model();
        $this->machineModel = new Machine_Model();
        $this->transitMaster = new TransitMaster();
        $this->indentAllotment = new IndentAllotmentModel();
        $this->planningProductionModel = new PlanningProductionModel();
        $this->mrMaterialModel = new MRMaterial_Model();
    }

    public function createAllocation()
    {
        $pendingIndents = $this->indentModel
            ->select('in_no, bill_to_code, ship_to_code')
            ->where('sap_init', 0)
            ->findAll();

        if (empty($pendingIndents)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No pending indents found'
            ]);
        }

        foreach ($pendingIndents as $key => $indent) {

            $orderDetails = $this->indentDetailsModel
                ->where('in_no', $indent['in_no'])
                ->findAll();

            $pendingIndents[$key]['order_details'] = $orderDetails;

            $customerType = $this->ppCustomerMaster
                ->select('CUSTOMER_TYPE')
                ->where('CUSTOMER_CODE', $indent['bill_to_code'])
                ->first();

            $pendingIndents[$key]['CUSTOMER_TYPE'] =
                $customerType['CUSTOMER_TYPE'] ?? null;

            $customerPinCode = $this->ppCustomerMaster
                ->select('PIN_CODE')
                ->where('CUSTOMER_CODE', $indent['ship_to_code'])
                ->first();

            $pendingIndents[$key]['CUSTOMER_PIN_CODE'] = $customerPinCode['PIN_CODE'] ?? null;


            foreach ($orderDetails as $odKey => $od) {

                $material = null;

                // material_code exists
                if (!empty($od['material_code'])) {

                    $material = $this->materialModel
                        ->select('ID, FINISH_MATERIAL_CODE, MR_MATERIAL_CODE, PACKAGING_TIME')
                        ->where('FINISH_MATERIAL_CODE', $od['material_code'])
                        ->first();

                } else {
                    $material = $this->materialModel
                        ->select('ID, FINISH_MATERIAL_CODE, MR_MATERIAL_CODE, PACKAGING_TIME')
                        ->where('GRADE', $od['item_variety'])
                        ->where('GSM', $od['gsm'])
                        ->where('WIDTH', $od['width'])
                        ->where('LENGTH', $od['length'])
                        ->first();
                }

                $pendingIndents[$key]['order_details'][$odKey]['FINISH_MATERIAL_CODE_ID'] =
                    $material['ID'] ?? null;

                $pendingIndents[$key]['order_details'][$odKey]['FINISH_MATERIAL_CODE'] =
                    $material['FINISH_MATERIAL_CODE'] ?? null;

                $pendingIndents[$key]['order_details'][$odKey]['MR_MATERIAL_CODE'] =
                    $material['MR_MATERIAL_CODE'] ?? null;

                $pendingIndents[$key]['order_details'][$odKey]['PACKAGING_TIME'] =
                    $material['PACKAGING_TIME'] ?? null;

                // Check Stock allocation
                $finishStockData = $this->finishStock
                    ->where('FINISH_MATERIAL_CODE', $pendingIndents[$key]['order_details'][$odKey]['FINISH_MATERIAL_CODE'])
                    ->first();

                $requiredQty = $pendingIndents[$key]['order_details'][$odKey]['quantity'];

                if (!empty($finishStockData) && $finishStockData['STOCK_QTY'] >= $requiredQty) {
                    // 1. Update indent_details (sap_init = 1)
                    $indentUpdated = $this->indentDetailsModel->update(
                        $pendingIndents[$key]['order_details'][$odKey]['id'],
                        ['sap_init' => 1]
                    );

                    // 2. If indent update successful, update finish_stock balance
                    if ($indentUpdated) {
                        $this->finishStock->update(
                            $finishStockData['PP_ID'],
                            [
                                'BALANCE_QTY' => $finishStockData['BALANCE_QTY'] - $requiredQty
                            ]
                        );
                    }
                    $pendingIndents[$key]['order_details'][$odKey]['balance_qty'] = $finishStockData['BALANCE_QTY'] - $requiredQty;
                    $pendingIndents[$key]['order_details'][$odKey]['plant'] = $finishStockData['SAP_PLANT'];
                    $pendingIndents[$key]['order_details'][$odKey]['stock'] = $finishStockData['STOCK_QTY'];
                    $pendingIndents[$key]['order_details'][$odKey]['booked'] = $requiredQty;
                    $pendingIndents[$key]['order_details'][$odKey]['fullfillment_flag'] = $pendingIndents[$key]['order_details'][$odKey]['sap_init'];

                    // Fetch machine related details
                    $machine = $this->machineModel
                        ->select('FINISH_LOSS_PERCENT, PIN_CODE')
                        ->where('SAP_PLANT', $pendingIndents[$key]['order_details'][$odKey]['plant'])
                        ->first();

                    $pendingIndents[$key]['order_details'][$odKey]['finish_loss_percent'] = $machine['FINISH_LOSS_PERCENT'];
                    $pendingIndents[$key]['order_details'][$odKey]['machine_pincode'] = $machine['PIN_CODE'];

                    // Fetch Transit data
                    $transit = $this->transitMaster
                        ->select('TRANSIT_TIME')
                        ->where('FROM_PINCODE', $pendingIndents[$key]['order_details'][$odKey]['machine_pincode'])
                        ->where('TO_PINCODE', $pendingIndents[$key]['CUSTOMER_PIN_CODE'])
                        ->first();

                    $pendingIndents[$key]['order_details'][$odKey]['transit_time'] = $transit['TRANSIT_TIME'];

                    // Update Allotment data
                    // FROM_DATE & TO_DATE = current datetime
                    $fromDate = date('Y-m-d H:i:s');
                    $toDate = date('Y-m-d H:i:s');

                    // FINISHING_DATE = TO_DATE + Packaging Time
                    $finishingDateTime = new \DateTime($toDate);
                    $finishingDateTime->add(new \DateInterval('P' . $pendingIndents[$key]['order_details'][$odKey]['PACKAGING_TIME'] . 'D'));
                    $finishingDate = $finishingDateTime->format('Y-m-d H:i:s');

                    // DOOR_STEP_DEL_DATE = FINISHING_DATE + Transit Time
                    $doorStepDateTime = new \DateTime($finishingDate);
                    $doorStepDateTime->add(new \DateInterval('P' . $pendingIndents[$key]['order_details'][$odKey]['transit_time'] . 'D'));
                    $doorStepDelDate = $doorStepDateTime->format('Y-m-d H:i:s');

                    $insertQuery = $this->indentAllotment->insert([
                        'INDENT_NO' => $pendingIndents[$key]['order_details'][$odKey]['in_no'],
                        'INDENT_LINE_ITEM' => (int) $pendingIndents[$key]['order_details'][$odKey]['line_item'],
                        'PLANNING_CAL_ID' => (int) ($pendingIndents[$key]['order_details'][$odKey]['planning_cal_id'] ?? 0),
                        'VERSION' => (int) ($pendingIndents[$key]['order_details'][$odKey]['version'] ?? 1),
                        'FINISH_MATERIAL_CODE' => $pendingIndents[$key]['order_details'][$odKey]['FINISH_MATERIAL_CODE'],
                        'MR_MATERIAL_CODE' => $pendingIndents[$key]['order_details'][$odKey]['MR_MATERIAL_CODE'],
                        'QUANTITY' => (int) $pendingIndents[$key]['order_details'][$odKey]['quantity'],
                        'FROM_DATE' => $fromDate,
                        'TO_DATE' => $toDate,
                        'FINISHING_DATE' => $finishingDate,
                        'DOOR_STEP_DEL_DATE' => $doorStepDelDate,
                        'CUSTOMER_TYPE' => $pendingIndents[$key]['CUSTOMER_TYPE'],
                        'CALENDAR_TYPE' => '',
                        'PO_NO' => '',
                        'PO_LINE_ITEM' => '',
                        'SCHEDULE_LINE_ITEM' => '',
                        'FULFILLMENT_FLAG' => (int) $pendingIndents[$key]['order_details'][$odKey]['fullfillment_flag'],
                        'SAP_ORDER_NO' => '',
                        'SAP_REMARKS' => '',
                    ]);
                    // TODO Add Success message for allotment completed
                } else {
                    // Query Production Planning
                    $query = $this->planningProductionModel
                        ->where('BALANCE_QTY !=', 0);
                    // ->where('FROM_DATE_TIME >=', date('Y-m-d H:i:s'));

                    $finishMaterialCodeId = $pendingIndents[$key]['order_details'][$odKey]['FINISH_MATERIAL_CODE_ID'] ?? null;

                    if (!empty($finishMaterialCodeId)) {
                        $query->where('SAP_MR_FG_CODE', $finishMaterialCodeId);
                    }

                    $planningData = $query->findAll();

                    // Loop Planning data and fetch machine type
                    foreach ($planningData as &$plan) {

                        // Make sure MACHINE ID exists
                        if (empty($plan['MACHINE'])) {
                            $plan['MACHINE_TYPE'] = null;
                            continue;
                        }

                        // Check TPM or Internal
                        $machine = $this->machineModel
                            ->select('TYPE, PIN_CODE')
                            ->where('PP_ID', $plan['MACHINE'])
                            ->first();

                        // Attach machine type to planning data
                        $plan['MACHINE_TYPE'] = $machine['TYPE'] ?? null;
                        $plan['MACHINE_PINCODE'] = $machine['PIN_CODE'] ?? null;
                    }

                    if (count($planningData) > 1) {

                        $machineTypes = array_column($planningData, 'MACHINE_TYPE');

                        $hasOwn = in_array('OWN', $machineTypes, true);
                        $hasTpm = in_array('TPM', $machineTypes, true);

                        if ($hasOwn && $hasTpm) {

                            // CONDITION 1 → records with MACHINE_TYPE = 'OWN'
                            $ownRecords = array_filter($planningData, function ($plan) {
                                return $plan['MACHINE_TYPE'] === 'OWN';
                            });

                            // CONDITION 2 → records with MACHINE_TYPE = 'TPM'
                            $tpmRecords = array_filter($planningData, function ($plan) {
                                return $plan['MACHINE_TYPE'] === 'TPM';
                            });

                            $internalRecords = [];

                            $getEarliestByMachine = function (array $records, string $machineKey = 'MACHINE') {
                                $earliest = [];
                                // var_dump($earliest);
                                foreach ($records as $record) {
                                    $machine = $record[$machineKey];

                                    if (
                                        !isset($earliest[$machine]) ||
                                        strtotime($record['FROM_DATE_TIME']) < strtotime($earliest[$machine]['FROM_DATE_TIME'])
                                    ) {
                                        $earliest[$machine] = $record;
                                    }
                                }

                                return array_values($earliest);
                            };

                            $earliestOwnRecords = $getEarliestByMachine(array_values($ownRecords));

                            $newearliestOwnRecords = [];

                            foreach ($earliestOwnRecords as $record) {
                                $defaultPlant = $this->mrMaterialModel
                                    ->select('DELIVERY_PLANT_YN')
                                    ->where('MR_MATERIAL_CODE', $pendingIndents[$key]['order_details'][$odKey]['MR_MATERIAL_CODE'])
                                    ->first();

                                $record['DEFAULT_PLANT'] = $defaultPlant['DELIVERY_PLANT_YN'] ?? null;
                                $newearliestOwnRecords[] = $record;
                            }

                            $earliestOwnRecords = $newearliestOwnRecords;

                            $internalRecords = array_merge($internalRecords, $earliestOwnRecords);

                            $earliestTpmRecords = $getEarliestByMachine(array_values($tpmRecords));
                            $internalRecords = array_merge($internalRecords, $earliestTpmRecords);

                            $newInternalRecords = [];

                            foreach ($internalRecords as $record) {
                                $transit = $this->transitMaster
                                    ->select('TRANSIT_TIME')
                                    ->where('FROM_PINCODE', $record['MACHINE_PINCODE'])
                                    ->where('TO_PINCODE', $pendingIndents[$key]['CUSTOMER_PIN_CODE'])
                                    ->first();

                                $record['TRANSIT_TIME'] = $transit['TRANSIT_TIME'] ?? null;
                                $newInternalRecords[] = $record;
                            }

                            $internalRecords = $newInternalRecords;

                            // Sort the data on TRANSIT_TIME and DEFAULT_PLANT
                            usort($internalRecords, function ($a, $b) {
                                // Compare TRANSIT_TIME ascending
                                $transitA = $a['TRANSIT_TIME'] ?? PHP_INT_MAX;
                                $transitB = $b['TRANSIT_TIME'] ?? PHP_INT_MAX;

                                if ($transitA !== $transitB) {
                                    return $transitA <=> $transitB;
                                }

                                // If TRANSIT_TIME is equal, compare DEFAULT_PLANT descending
                                $plantA = $a['DEFAULT_PLANT'] ?? '';
                                $plantB = $b['DEFAULT_PLANT'] ?? '';
                                return $plantB <=> $plantA;
                            });

                            
                            $allocationRecord = $internalRecords[0];

                            if ($allocationRecord['MACHINE_TYPE'] === "TPM") {
                                $allocationRecord['booked_qty'] = $pendingIndents[$key]['order_details'][$odKey]['quantity'];
                                $allocationRecord['calendar_id'] = $allocationRecord['PP_ID'];
                                $allocationRecord['version'] = $allocationRecord['VERSION'];
                                $allocationRecord['new_from_date'] = $allocationRecord['FROM_DATE_TIME'];
                                $allocationRecord['new_to_date'] = $allocationRecord['TO_DATE_TIME'];
                                $allocationRecord['finishing_date'] = $allocationRecord['TO_DATE_TIME'];
                                $allocationRecord['door_step_delivery_date'] = (int) $allocationRecord['TRANSIT_TIME'] + (int) $allocationRecord['TO_DATE_TIME'];
                                $allocationRecord['calendar_type'] = "T";

                                // Update Production Planning Master
                                $customer_type = $pendingIndents[$key]['CUSTOMER_TYPE'];
                                $actual_qty = $pendingIndents[$key]['order_details'][$odKey]['quantity'];

                                $this->planningProductionModel->update(
                                    $allocationRecord['PP_ID'],
                                    [
                                        $customer_type . '_UTILISED_QTY_MT' => (int) $allocationRecord[$customer_type . '_UTILISED_QTY_MT'] + (int) $actual_qty,
                                        'UTILISED_QTY' => (int) $allocationRecord['UTILISED_QTY'] + (int) $actual_qty,
                                        $customer_type . '_BALANCE_QTY_MT' => (int) $allocationRecord[$customer_type . '_BALANCE_QTY_MT'] - (int) $actual_qty,
                                        'BALANCE_QTY' => (int) $allocationRecord['BALANCE_QTY'] - (int) $actual_qty
                                    ]
                                );

                            } else {


                            $ppIds = array_column($earliestOwnRecords, 'PP_ID');

                            $latestRecords = $this->indentAllotment
                                ->select('MAX(TO_DATE) as latest_to_date')
                                ->whereIn('PLANNING_CAL_ID', $ppIds)
                                ->groupBy('PLANNING_CAL_ID')
                                ->findAll();

                            if(count($latestRecords) > 0) {
                                // TODO test data missing
                            } else {
                                
                                // need to check requirements. using earliestOwnRecords[0]
                                $machineData = $this->machineModel
                                    ->select('PP_ID, GSM_CHANGE_TIME_MIN, GRADE_CHANGE_TIME_MIN, SAP_PLANT')
                                    ->where('PP_ID', $earliestOwnRecords[0]['MACHINE'])
                                    ->first();
                                
                                $machinePPId = $machineData['PP_ID'];
                                $gsmChangeTime = $machineData['GSM_CHANGE_TIME_MIN'];
                                $gradeChangeTime = $machineData['GRADE_CHANGE_TIME_MIN'];
                                $sapPlant = $machineData['SAP_PLANT'];

                                $motherRoll = $this->mrMaterialModel
                                    ->where('PP_ID', $earliestOwnRecords[0]['SAP_MR_FG_CODE'])
                                    ->first();
                                

                                if (!$motherRoll) {
                                    return redirect()->back()->with(
                                        'error',
                                        "Material not found."
                                    );
                                }

                                $motherRollPPId = $motherRoll['PP_ID'];
                                $machineOutputKgHr = $motherRoll['MACHINE_OUTPUT_KG_HR'];
                                $grade = $motherRoll['GRADE'];
                                $gsm = $motherRoll['GSM'];

                                $plannedQtyKg = $pendingIndents[$key]['order_details'][$odKey]['quantity']; // in kg
                                $productionHour = ($machineOutputKgHr > 0)
                                                    ? ($plannedQtyKg / $machineOutputKgHr)
                                                    : 0;
                                $hours = floor($productionHour);
                                $minutes = round(($productionHour - $hours) * 60);

                                // END TIME = FROM_TIME + PRODUCTION TIME
                                $toDateTime = new \DateTime($earliestOwnRecords[0]['FROM_DATE_TIME']);
                                $toDateTime->modify("+{$hours} hours +{$minutes} minutes");

                                // Final toDate string values
                                $toDateTimeStr = $toDateTime->format('Y-m-d H:i:s');

                                // Final finishing date after adding packaging time
                                $packagingHours = (int)$pendingIndents[$key]['order_details'][$odKey]['PACKAGING_TIME']; // "48"
                                $finalFinishingDate = new \DateTime($toDateTimeStr);
                                $finalFinishingDate->modify("+{$packagingHours} hours");
                                $finalFinishingDateFormatted = $finalFinishingDate->format('Y-m-d H:i:s');

                                // Final door step delivery date after adding transit time
                                $transitHours = (int)$allocationRecord['TRANSIT_TIME']; // "24"
                                $finalDoorStepDate = new \DateTime($finalFinishingDateFormatted);
                                $finalDoorStepDate->modify("+{$transitHours} hours");
                                $finalDoorStepDateFormatted = $finalDoorStepDate->format('Y-m-d H:i:s');
                                
                                $allocationRecord['booked_qty'] = $pendingIndents[$key]['order_details'][$odKey]['quantity'];
                                $allocationRecord['calendar_id'] = $allocationRecord['PP_ID'];
                                $allocationRecord['version'] = $allocationRecord['VERSION'];
                                $allocationRecord['new_from_date'] = $allocationRecord['FROM_DATE_TIME'];
                                $allocationRecord['new_to_date'] = $toDateTimeStr;
                                $allocationRecord['finishing_date'] = $finalFinishingDateFormatted;
                                $allocationRecord['door_step_delivery_date'] = $finalDoorStepDateFormatted;
                                $allocationRecord['calendar_type'] = "M";

                                // Update Production Planning Master
                                $customer_type = $pendingIndents[$key]['CUSTOMER_TYPE'];
                                $actual_qty = $pendingIndents[$key]['order_details'][$odKey]['quantity'];

                                $this->planningProductionModel->update(
                                    $allocationRecord['PP_ID'],
                                    [
                                        $customer_type . '_UTILISED_QTY_MT' => (int) $allocationRecord[$customer_type . '_UTILISED_QTY_MT'] + (int) $actual_qty,
                                        'UTILISED_QTY' => (int) $allocationRecord['UTILISED_QTY'] + (int) $actual_qty,
                                        $customer_type . '_BALANCE_QTY_MT' => (int) $allocationRecord[$customer_type . '_BALANCE_QTY_MT'] - (int) $actual_qty,
                                        'BALANCE_QTY' => (int) $allocationRecord['BALANCE_QTY'] - (int) $actual_qty
                                    ]
                                );
                            }   
                            }
                        }
                    }
                }
            }
        }

        return $this->response->setJSON([
            'status' => true,
            'count' => count($pendingIndents),
            'data' => $pendingIndents
        ]);
    }

}
