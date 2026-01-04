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
            ->select('id, in_no, bill_to_code, ship_to_code')
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
                ->where('sap_init', 0)
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

                if (!empty($finishStockData) && $finishStockData['BALANCE_QTY'] >= $requiredQty) {
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

                    $this->indentAllotment->insert([
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
                        'CALENDAR_TYPE' => 'S',
                        'PO_NO' => '',
                        'PO_LINE_ITEM' => '',
                        'SCHEDULE_LINE_ITEM' => '',
                        'FULFILLMENT_FLAG' => 1,
                        'SAP_ORDER_NO' => '',
                        'SAP_REMARKS' => '',
                    ]);
                } else {
                    // Query Production Planning
                    $currentDateTime = date('Y-m-d H:i:s');

                    $customerTypeBalanceQtyField = $pendingIndents[$key]['CUSTOMER_TYPE'] . "_BALANCE_QTY_MT";

                    $baseQuery = $this->planningProductionModel
                        ->where($customerTypeBalanceQtyField . ' >=', (int) $pendingIndents[$key]['order_details'][$odKey]['quantity'])
                        ->where('FROM_DATE_TIME >=', $currentDateTime);

                    $mrMaterialCode = $pendingIndents[$key]['order_details'][$odKey]['MR_MATERIAL_CODE'] ?? null;
                    $finishMaterialCode = $pendingIndents[$key]['order_details'][$odKey]['FINISH_MATERIAL_CODE'] ?? null;

                    $planningData = [];

                    if (!empty($mrMaterialCode)) {
                        $query = clone $baseQuery;
                        $planningData = $query
                            ->where('SAP_MR_FG_CODE', $mrMaterialCode)
                            ->findAll();
                    }

                    if (empty($planningData) && !empty($finishMaterialCode)) {
                        $query = clone $baseQuery;
                        $planningData = $query
                            ->where('SAP_MR_FG_CODE', $finishMaterialCode)
                            ->findAll();
                    }

                    // echo "<pre>"; print_r($planningData); exit;
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

                    $finalRecords = [];

                    if (count($planningData) >= 1) {

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

                            //  echo "<pre>"; print_r($tpmRecords); exit;

                            $internalRecords = [];

                            $getEarliestByMachine = function (array $records, string $machineKey = 'MACHINE') {
                                $earliest = [];

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
                            
                            //  echo "<pre>"; print_r($internalRecords); exit;

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

                            //  echo "<pre>"; print_r($internalRecords); exit;
                            $finalRecords = $internalRecords;

                        } elseif ($hasOwn && !$hasTpm) {

                            // CASE 2: ONLY OWN
                            $ownRecords = array_filter($planningData, function ($plan) {
                                return $plan['MACHINE_TYPE'] === 'OWN';
                            });

                            $getEarliestByMachine = function (array $records, string $machineKey = 'MACHINE') {
                                $earliest = [];
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

                            // Enrich DEFAULT_PLANT
                            foreach ($earliestOwnRecords as &$record) {
                                $defaultPlant = $this->mrMaterialModel
                                    ->select('DELIVERY_PLANT_YN')
                                    ->where(
                                        'MR_MATERIAL_CODE',
                                        $pendingIndents[$key]['order_details'][$odKey]['MR_MATERIAL_CODE']
                                    )
                                    ->first();

                                $record['DEFAULT_PLANT'] = $defaultPlant['DELIVERY_PLANT_YN'] ?? null;
                            }

                            // Add TRANSIT_TIME
                            foreach ($earliestOwnRecords as &$record) {
                                $transit = $this->transitMaster
                                    ->select('TRANSIT_TIME')
                                    ->where('FROM_PINCODE', $record['MACHINE_PINCODE'])
                                    ->where('TO_PINCODE', $pendingIndents[$key]['CUSTOMER_PIN_CODE'])
                                    ->first();

                                $record['TRANSIT_TIME'] = $transit['TRANSIT_TIME'] ?? null;
                            }

                            // Sort by TRANSIT_TIME, DEFAULT_PLANT
                            usort($earliestOwnRecords, function ($a, $b) {
                                return ($a['TRANSIT_TIME'] ?? PHP_INT_MAX)
                                    <=> ($b['TRANSIT_TIME'] ?? PHP_INT_MAX)
                                    ?: (($b['DEFAULT_PLANT'] ?? '') <=> ($a['DEFAULT_PLANT'] ?? ''));
                            });

                            $finalRecords = $earliestOwnRecords;

                        } elseif (!$hasOwn && $hasTpm) {

                            // CASE 3: ONLY TPM
                            $tpmRecords = array_filter($planningData, function ($plan) {
                                return $plan['MACHINE_TYPE'] === 'TPM';
                            });

                            $getEarliestByMachine = function (array $records, string $machineKey = 'MACHINE') {
                                $earliest = [];
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

                            $earliestTpmRecords = $getEarliestByMachine(array_values($tpmRecords));

                            // Add TRANSIT_TIME
                            foreach ($earliestTpmRecords as &$record) {
                                $transit = $this->transitMaster
                                    ->select('TRANSIT_TIME')
                                    ->where('FROM_PINCODE', $record['MACHINE_PINCODE'])
                                    ->where('TO_PINCODE', $pendingIndents[$key]['CUSTOMER_PIN_CODE'])
                                    ->first();

                                $record['TRANSIT_TIME'] = $transit['TRANSIT_TIME'] ?? null;
                            }

                            // Sort by TRANSIT_TIME
                            usort($earliestTpmRecords, function ($a, $b) {
                                return ($a['TRANSIT_TIME'] ?? PHP_INT_MAX)
                                    <=> ($b['TRANSIT_TIME'] ?? PHP_INT_MAX);
                            });

                            $finalRecords = $earliestTpmRecords;

                        } else {
                            // CASE 4: NO MACHINES AVAILABLE
                            return redirect()->back()->with(
                                'error',
                                'No planning data available for OWN or TPM machines.'
                            );
                        }

                        $allocationRecord = $finalRecords[0];

                            //  echo "<pre>"; print_r($allocationRecord); exit;
                        if ($allocationRecord['MACHINE_TYPE'] === "TPM") {
                            $allocationRecord['booked_qty'] = $pendingIndents[$key]['order_details'][$odKey]['quantity'];
                            $allocationRecord['calendar_id'] = $allocationRecord['PP_ID'];
                            $allocationRecord['version'] = $allocationRecord['VERSION'];
                            $allocationRecord['new_from_date'] = $allocationRecord['FROM_DATE_TIME'];
                            $allocationRecord['new_to_date'] = $allocationRecord['TO_DATE_TIME'];
                            $allocationRecord['finishing_date'] = $allocationRecord['TO_DATE_TIME'];

                            $toDateTime = new \DateTime($allocationRecord['TO_DATE_TIME']);
                            $transitDays = (int) $allocationRecord['TRANSIT_TIME'];
                            $toDateTime->add(new \DateInterval('P' . $transitDays . 'D'));
                            $allocationRecord['door_step_delivery_date'] = $toDateTime->format('Y-m-d H:i:s');

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

                            $motherRoll = $this->mrMaterialModel
                                ->where('MR_MATERIAL_CODE', $earliestOwnRecords[0]['SAP_MR_FG_CODE'])
                                ->first();


                            // echo "<pre>"; print_r($motherRoll); exit;

                            if (!$motherRoll) {
                                return redirect()->back()->with(
                                    'error',
                                    "Material not found."
                                );
                            }

                            $machineOutputKgHr = $motherRoll['MACHINE_OUTPUT_KG_HR'];

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
                            $packagingHours = (int) $pendingIndents[$key]['order_details'][$odKey]['PACKAGING_TIME'];
                            $finalFinishingDate = new \DateTime($toDateTimeStr);
                            $finalFinishingDate->modify("+{$packagingHours} hours");
                            $finalFinishingDateFormatted = $finalFinishingDate->format('Y-m-d H:i:s');

                            // Final door step delivery date after adding transit time
                            $transitDays = (int) $allocationRecord['TRANSIT_TIME'];
                            $finalDoorStepDate = new \DateTime($finalFinishingDateFormatted);
                            $finalDoorStepDate->add(new \DateInterval('P' . $transitDays . 'D'));
                            $finalDoorStepDateFormatted = $finalDoorStepDate->format('Y-m-d H:i:s');

                            $allocationRecord['booked_qty'] = $pendingIndents[$key]['order_details'][$odKey]['quantity'];
                            $allocationRecord['calendar_id'] = $allocationRecord['PP_ID'];
                            $allocationRecord['version'] = $allocationRecord['VERSION'];
                            $allocationRecord['new_from_date'] = $latestRecords[0]['latest_to_date'] ?? $allocationRecord['FROM_DATE_TIME'];
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
                        // Update Indent Allotment 
                        $data = [
                            'INDENT_NO' => $pendingIndents[$key]['order_details'][$odKey]['in_no'],
                            'INDENT_LINE_ITEM' => $pendingIndents[$key]['order_details'][$odKey]['line_item'],
                            'PLANNING_CAL_ID' => $allocationRecord['PP_ID'],
                            'VERSION' => $allocationRecord['VERSION'],
                            'FINISH_MATERIAL_CODE' => $pendingIndents[$key]['order_details'][$odKey]['FINISH_MATERIAL_CODE'],
                            'MR_MATERIAL_CODE' => $pendingIndents[$key]['order_details'][$odKey]['MR_MATERIAL_CODE'],
                            'QUANTITY' => $pendingIndents[$key]['order_details'][$odKey]['quantity'],
                            'FROM_DATE' => $allocationRecord['new_from_date'],
                            'TO_DATE' => $allocationRecord['new_to_date'],
                            'FINISHING_DATE' => $allocationRecord['finishing_date'],
                            'DOOR_STEP_DEL_DATE' => $allocationRecord['door_step_delivery_date'],
                            'CUSTOMER_TYPE' => $pendingIndents[$key]['CUSTOMER_TYPE'],
                            'CALENDAR_TYPE' => $allocationRecord['CALENDAR_TYPE'],
                            'PO_NO' => $allocationRecord['PO_NO'],
                            'PO_LINE_ITEM' => $allocationRecord['PO_LINE_ITEM'],
                            'FULFILLMENT_FLAG' => 1,
                            'SCHEDULE_LINE_ITEM' => $allocationRecord['SCHEDULE_LINE_ITEM']
                        ];
                        $this->indentAllotment->insert($data);

                        // Update indent_details (sap_init = 1)
                        $indentUpdated = $this->indentDetailsModel->update(
                            $pendingIndents[$key]['order_details'][$odKey]['id'],
                            ['sap_init' => 1]
                        );
                    }
                }

                // Update indent_header (sap_init = 1)
                $orderDetailsUpdated = $this->indentDetailsModel
                    ->where('in_no', $indent['in_no'])
                    ->findAll();

                $allSapInitOne = true;

                foreach ($orderDetailsUpdated as $order) {
                    if ((int) $order['sap_init'] !== 1) {
                        $allSapInitOne = false;
                        break;
                    }
                }

                if ($allSapInitOne) {
                    $indentUpdated = $this->indentModel->update(
                        $pendingIndents[$key]['id'],
                        ['sap_init' => 1]
                    );
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
