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

            if (empty($item['finishmaterialcode']) || empty($item['sapplant'])) {
                $responseData[] = [
                    'row' => $index,
                    'status' => 'Failed',
                    'message' => 'FINISH_MATERIAL_CODE or SAP_PLANT missing'
                ];
                continue;
            }


            // Update database
            $payload = [
                'FINISH_MATERIAL_CODE' => $item['finishmaterialcode'],
                'SAP_PLANT' => $item['sapplant'],
                'STOCK_QTY'    => $item['stockqty'] ?? 0,
                'BALANCE_QTY'    => $item['balanceqty'] ?? 0,
            ];

            $exists = $model->where('FINISH_MATERIAL_CODE', $item['finishmaterialcode'])
                ->where('SAP_PLANT', $item['sapplant'])
                ->first();

            if ($exists) {

                $model->where('FINISH_MATERIAL_CODE', $item['finishmaterialcode'])
                      ->where('SAP_PLANT', $item['sapplant'])
                      ->set([
                        'STOCK_QTY'   => $payload['STOCK_QTY'],
                        'BALANCE_QTY' => $payload['BALANCE_QTY']
                        ])
                        ->update();

                $responseData[] = [
                    'row' => $index,
                    'status' => $item['finishmaterialcode'],
                    'message' => 'Updated'
                ];
            } else {


                $model->insert($payload);

                $responseData[] = [
                    'row' => $index,
                    'status' => $item['finishmaterialcode'],
                    'message' => 'Inserted'
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
