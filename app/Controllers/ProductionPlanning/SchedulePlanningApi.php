<?php

namespace App\Controllers\ProductionPlanning;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductionPlanning\PlanningProductionModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class SchedulePlanningApi extends ResourceController
{
    public function UpdatePlanning()
    {
        // Read JSON payload
        $data = $this->request->getJSON(true);

        if (!$data || !is_array($data)) {
            return $this->fail('Invalid JSON payload');
        }

        $model = new PlanningProductionModel();
        $responseData = [];

        $db = \Config\Database::connect();
        $db->transStart(); // start transaction

        foreach ($data as $index => $item) {

            // Validation: required fields
            if (
                empty($item['vendorcode']) ||
                empty($item['finishmaterialcode']) ||
                empty($item['fromdate']) ||
                empty($item['todate'])
            ) {
                $responseData[] = [
                    'row' => $index,
                    'status' => 'Failed',
                    'message' => 'Vendor / Material Code / From Date / To Date missing'
                ];
                continue;
            }

            // Fetch machine master
            $machine = $db->table('pp_machine_master')
                ->where('SAP_VENDOR_CODE', $item['vendorcode'])
                ->get()
                ->getRowArray();

            if (!$machine) {
                $responseData[] = [
                    'row' => $index,
                    'status' => 'Failed',
                    'message' => 'Machine not found for Vendor' . $item['vendorcode']
                ];
                continue;
            }

            $material = $db->table('pp_mr_material_master')
                ->where('FINISH_MATERIAL_CODE', $item['finishmaterialcode'])
                ->where('SAP_PLANT', $item['sapplant'])
                ->get()
                ->getRowArray();

            if (!$material) {

                $responseData[] = [
                    'row' => $index,
                    'status' => 'Failed',
                    'message' => "Material not found:" . $item['finishmaterialcode'] . "(Plant:" . $item['sapplant'] . ")"
                ];
                continue;
            }

            $grade = $material['GRADE'];

            // Fetch quota
            $quota = $db->table('pp_customer_quota_master')
                ->where('GRADE', $grade)
                ->orderBy('CUSTOMER_TYPE', 'ASC')
                ->get()
                ->getResultArray();

            $kc1Quota = 0;
            $kc2Quota = 0;

            if (is_array($quota)) {
                if (!empty($quota[0]['QUOTA_PERCENTAGE'])) {
                    $kc1Quota = $item['scheduleqty'] * $quota[0]['QUOTA_PERCENTAGE'] / 100;
                }

                if (!empty($quota[1]['QUOTA_PERCENTAGE'])) {
                    $kc2Quota = $item['scheduleqty'] * $quota[1]['QUOTA_PERCENTAGE'] / 100;
                }
            }

            $nkcQuota = $item['scheduleqty'] - ($kc1Quota + $kc2Quota);

            // Final string values
            $fromDateTimeStr = $item['fromdate']->format('Y-m-d H:i:s');
            $toDateTimeStr = $item['todate']->format('Y-m-d H:i:s');

            // Prepare payload for DB (map JSON to table columns)
            $payload = [
                'VERSION' => 1,
                'MACHINE' => $machine['PP_ID'],
                'SAP_MR_FG_CODE' => $item['finishmaterialcode'],
                'QTY_MT' => $item['scheduleqty'],
                'BALANCE_QTY' => $item['scheduleqty'],
                'KC1_QTY_MT' => $kc1Quota,
                'KC2_QTY_MT' => $kc2Quota,
                'NKC_QTY_MT' => $nkcQuota,
                'KC1_BALANCE_QTY_MT' => $kc1Quota,
                'KC2_BALANCE_QTY_MT' => $kc2Quota,
                'NKC_BALANCE_QTY_MT' => $nkcQuota,
                'FROM_DATE_TIME' => $fromDateTimeStr,
                'TO_DATE_TIME' => $toDateTimeStr,
                'CALENDAR_TYPE' => 'T',
                'PO_NO' => $item['pono'],
                'PO_LINE_ITEM' => $item['polineitem'],
                'SCHEDULE_LINE_ITEM' => $item['schedulelineitem'],
                'UPLOADED_BY' => 'SAP',
                'UPLOADED_DATE' => date('Y-m-d H:i:s'),
            ];

            // Check if this record already exists
            $exists = $model->where('MACHINE', $machine['PP_ID'])
                ->where('SAP_MR_FG_CODE', $item['finishmaterialcode'])
                ->where('FROM_DATE_TIME', $fromDateTimeStr)
                ->where('TO_DATE_TIME', $toDateTimeStr)
                ->first();

            if ($exists) {
                // Update existing record
                $model->where('PP_ID', $exists['PP_ID'])
                    ->set($payload)
                    ->update();

                $responseData[] = [
                    'row' => $index,
                    'status' => $item['pono'],
                    'message' => 'Updated'
                ];
            } else {
                // Insert new record
                $model->insert($payload);

                $responseData[] = [
                    'row' => $index,
                    'status' => $item['pono'],
                    'message' => 'Inserted'
                ];
            }
        }

        $db->transComplete(); // complete transaction

        if ($db->transStatus() === false) {
            return $this->failServerError('Database transaction failed');
        }

        return $this->respond([
            'status' => true,
            'message' => 'Production Planning update completed',
            'data' => $responseData
        ], 200);
    }
}
