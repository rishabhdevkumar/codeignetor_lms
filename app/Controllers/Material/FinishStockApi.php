<?php

namespace App\Controllers\Material;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Material\FinishStockModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class FinishStockApi extends ResourceController
{
    public function UpdateFinishStock()
    {

        // Read JSON payload
        $data = $this->request->getJSON(true);


        if (!$data || !is_array($data)) {
            return $this->fail('Invalid JSON payload');
        }

        $model = new FinishStockModel();
        $responseData = [];


        $db = \Config\Database::connect();

        $db->transStart();

        foreach ($data as $index => $item) {

            if (empty($item['finish_material_code']) || empty($item['sap_plant'])) {
                $responseData[] = [
                    'row' => $index,
                    'status' => 'Failed',
                    'message' => 'FINISH_MATERIAL_CODE or SAP_PLANT missing'
                ];
                continue;
            }


            // Update database
            $payload = [
                'FINISH_MATERIAL_CODE' => $item['finish_material_code'],
                'SAP_PLANT' => $item['sap_plant'],
                'STOCK_QTY'    => $item['stock_qty'] ?? 0,
                'BALANCE_QTY'    => $item['balance_qty'] ?? 0,
            ];

            $exists = $model->where('FINISH_MATERIAL_CODE', $item['finish_material_code'])
                ->where('SAP_PLANT', $item['sap_plant'])
                ->first();

            if ($exists) {

                $model->where('FINISH_MATERIAL_CODE', $item['finish_material_code'])
                      ->where('SAP_PLANT', $item['sap_plant'])
                      ->set([
                        'STOCK_QTY'   => $payload['STOCK_QTY'],
                        'BALANCE_QTY' => $payload['BALANCE_QTY']
                        ])
                        ->update();

                $responseData[] = [
                    'finish_material_code' => $item['finish_material_code'],
                    'sap_plant' => $item['sap_plant'],
                    'action' => 'Updated'
                ];
            } else {


                $model->insert($payload);

                $responseData[] = [
                    'finish_material_code' => $item['finish_material_code'],
                    'sap_plant' => $item['sap_plant'],
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
            'message' => 'Finish Stock Update completed successfully',
            'data' => $responseData
        ], 200);
    }
}
