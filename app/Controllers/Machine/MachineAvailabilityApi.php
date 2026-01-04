<?php

namespace App\Controllers\Machine;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Machine\MachineAvailabilityModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class FinishStockApi extends ResourceController
{
    public function UpdateMachineAvailability()
    {

        // Read JSON payload
        $data = $this->request->getJSON(true);


        if (!$data || !is_array($data)) {
            return $this->fail('Invalid JSON payload');
        }

        $model = new MachineAvailabilityModel();
        $responseData = [];


        $db = \Config\Database::connect();

        $db->transStart();

        foreach ($data as $index => $item) {

            if (empty($item['machine_tpm_id']) || empty($item['sap_notification_no'])) {
                $responseData[] = [
                    'row' => $index,
                    'status' => 'Failed',
                    'message' => 'Machine ID or Notification No. missing'
                ];
                continue;
            }


            // Update database
            $payload = [
                'MACHINE_TPM_ID' => $item['machine_tpm_id'],
                'SAP_NOTIFICATION_NO' => $item['sap_notification_no'],
                'TYPE'    => $item['type'] ,
                'FROM_DATE'    => $item['from_date'] ,
                'TO_DATE'    => $item['to_date'] ,
            ];

            $exists = $model->where('MACHINE_TPM_ID', $item['machine_tpm_id'])
                ->where('SAP_NOTIFICATION_NO', $item['sap_notification_no'])
                ->first();

            if ($exists) {

                $model->where('MACHINE_TPM_ID', $item['machine_tpm_id'])
                      ->where('SAP_NOTIFICATION_NO', $item['sap_notification_no'])
                      ->set([
                        'FROM_DATE'   => $payload['FROM_DATE'],
                        'TO_DATE' => $payload['TO_DATE']
                        ])
                        ->update();

                $responseData[] = [
                    'machine_tpm_id' => $item['machine_tpm_id'],
                    'sap_notification_no' => $item['sap_notification_no'],
                    'action' => 'Updated'
                ];
            } else {


                $model->insert($payload);

                $responseData[] = [
                    'machine_tpm_id' => $item['machine_tpm_id'],
                    'sap_notification_no' => $item['sap_notification_no'],
                    'action' => 'Inserted'
                ];
            }
        }


        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Database transaction failed');
        }

        return $this->respond([
            'status' => true,
            'message' => 'Machine Availability Update completed successfully',
            'data' => $responseData
        ], 200);
    }
}
