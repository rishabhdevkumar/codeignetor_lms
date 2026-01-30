<?php

namespace App\Controllers\Material;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Material\MaterialModel;
use App\Models\Material\MRMaterialModel;

class MaterialMasterApi extends ResourceController
{
    public function UpdateMaterialMaster()
    {
        // Read JSON payload
        $data = $this->request->getJSON(true);

        if (!$data || !is_array($data)) {
            return $this->fail('Invalid JSON payload');
        }

        $model = new MaterialModel();
        $mrmaterialModel  = new MRMaterialModel();
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

            $mrpayload = [
                'MR_MATERIAL_CODE'       => $item['mrmaterialcode'],
                'SAP_PLANT'              => $item['sapplant'],
                'GRADE'                  => $item['grade'] ?? null,
                'GSM'                    => $item['gsm'] ?? null,
                'DELIVERY_PLANT_YN'      => $item['deliveryplant'],
                'MACHINE_OUTPUT_KG_HR'   => $item['machineoutput'] ?? null,
                'DESCRIPTION'            => $item['mrmaterialdesc'] ?? null,
            ];

            $mrexists = $mrmaterialModel->where('MR_MATERIAL_CODE', $item['mrmaterialcode'])
                            ->where('SAP_PLANT', $item['sapplant'])
                            ->first();

            if ($mrexists) {

                $mrmaterialModel->where('MR_MATERIAL_CODE', $item['mrmaterialcode'])
                      ->where('SAP_PLANT', $item['sapplant'])
                      ->set($mrpayload)
                      ->update();

            } else {

                $mrmaterialModel->insert($mrpayload);

            }

             $payload = [
                'FINISH_MATERIAL_CODE' => $item['finishmaterialcode'],
                'SAP_PLANT'           => $item['sapplant'],
                'GRADE'               => $item['grade'] ?? null,
                'GSM'                 => $item['gsm'] ?? null,
                'UOM'                 => $item['uom'] ?? null,
                'ITEM_TYPE'           => $item['itemtype'] ?? null,
                'WIDTH'               => $item['width'] ?? null,
                'LENGTH'              => $item['length'] ?? null,
                'MR_MATERIAL_CODE'    => $item['mrmaterialcode'] ?? null,
                'PACKAGING_TIME'      => $item['packagingtime'] ?? null,
                'DESCRIPTION'         => $item['description'] ?? null,
            ];

            $finishexists = $model->where('FINISH_MATERIAL_CODE', $item['finishmaterialcode'])
                            ->where('SAP_PLANT', $item['sapplant'])
                            ->first();

            if ($finishexists) {

                $model->where('FINISH_MATERIAL_CODE', $item['finishmaterialcode'])
                      ->where('SAP_PLANT', $item['sapplant'])
                      ->set($payload)
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
            'message' => 'Material Master update completed successfully',
            'data' => $responseData
        ], 200);
    }
}
