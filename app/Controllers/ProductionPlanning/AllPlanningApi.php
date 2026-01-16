<?php

namespace App\Controllers\ProductionPlanning;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductionPlanning\PlanningProductionModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class AllPlanningApi extends ResourceController
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
                empty($item['machine']) ||
                empty($item['sap_mr_fg_code']) ||
                empty($item['from_date_time']) ||
                empty($item['to_date_time'])
            ) {
                $responseData[] = [
                    'row' => $index,
                    'status' => 'Failed',
                    'message' => 'MACHINE / SAP_MR_FG_CODE / FROM_DATE_TIME / TO_DATE_TIME missing'
                ];
                continue;
            }

            // Prepare payload for DB (map JSON to table columns)
            $payload = [
                'VERSION'               => $item['version'] ?? null,
                'MACHINE'               => $item['machine'],
                'SAP_MOTHER_ROLL_CODE'  => $item['sap_mr_fg_code'],
                'QTY_MT'                => $item['qty_mt'] ?? 0,
                'FROM_DATE_TIME'        => $item['from_date_time'],
                'TO_DATE_TIME'          => $item['to_date_time'],
            ];

            // Check if this record already exists
            $exists = $model->where('MACHINE', $item['machine'])
                            ->where('SAP_MOTHER_ROLL_CODE', $item['sap_mr_fg_code'])
                            ->where('FROM_DATE_TIME', $item['from_date_time'])
                            ->where('TO_DATE_TIME', $item['to_date_time'])
                            ->first();

            if ($exists) {
                // Update existing record
                $model->where('PP_ID', $exists['PP_ID'])
                      ->set($payload)
                      ->update();

                $responseData[] = [
                    'machine' => $item['machine'],
                    'sap_mr_fg_code' => $item['sap_mr_fg_code'],
                    'action' => 'Updated'
                ];
            } else {
                // Insert new record
                $model->insert($payload);

                $responseData[] = [
                    'machine' => $item['machine'],
                    'sap_mr_fg_code' => $item['sap_mr_fg_code'],
                    'action' => 'Inserted'
                ];
            }
        }

        $db->transComplete(); // complete transaction

        if ($db->transStatus() === false) {
            return $this->failServerError('Database transaction failed');
        }

        return $this->respond([
            'status' => true,
            'message' => 'Production Planning update completed successfully',
            'data' => $responseData
        ], 200);
    }
}
