<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DealerTargetDispatchModel;

class DealerTargetDispatchApi extends ResourceController
{
    public function UpdateDealerTarget()
    {
        // Read JSON payload
        $data = $this->request->getJSON(true);

        if (!$data || !is_array($data)) {
            return $this->fail('Invalid JSON payload');
        }

        $model = new DealerTargetDispatchModel();
        $responseData = [];

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($data as $index => $item) {

            if (empty($item['customercode'])) {
                $responseData[] = [
                    'row' => $index,
                    'status' => 'Failed',
                     'message' => 'Customer Code Missing'
                    // 'message' => $item
                ];
                continue;
            }

            $payload = [
                'cust_no'       => $item['customercode'],
                'target'        => $item['target'] ?? 0,
                'dispatch'      => $item['dispatch'] ?? 0,
                'updated_at'    => date('Y-m-d H:i:s'),
            ];


            $customerexists = $model->where('cust_no', $item['customercode'])
                            ->first();

            if ($customerexists) {

                $model->where('cust_no', $item['customercode'])
                      ->set($payload)
                      ->update();

                $responseData[] = [
                    'row' => $index,
                    'status' => $item['customercode'],
                    'message' => 'Updated'
                ];

            } else {

                $model->insert($payload);

                $responseData[] = [
                    'row' => $index,
                    'status' => $item['customercode'],
                    'message' => 'Inserted'
                ];
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Database Transaction failed');
        }

        return $this->respond([
            'status' => true,
            'message' => 'Customer Target Dispatch Update Completed Successfully',
            'data' => $responseData
        ], 200);
    }
}
