<?php

namespace App\Controllers\Material;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Material\MaterialModel;

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

            $payload = [
                'FINISH_MATERIAL_CODE' => $item['finish_material_code'],
                'SAP_PLANT'           => $item['sap_plant'],
                'GRADE'               => $item['grade'] ?? null,
                'GSM'                 => $item['gsm'] ?? null,
                'UOM'                 => $item['uom'] ?? null,
                'ITEM_TYPE'           => $item['item_type'] ?? null,
                'WIDTH'               => $item['width'] ?? null,
                'LENGTH'              => $item['length'] ?? null,
                'MR_MATERIAL_CODE'    => $item['mr_material_code'] ?? null,
                'PACKAGING_TIME'      => $item['packaging_time'] ?? null,
            ];

            $exists = $model->where('FINISH_MATERIAL_CODE', $item['finish_material_code'])
                            ->where('SAP_PLANT', $item['sap_plant'])
                            ->first();

            if ($exists) {

                $model->where('FINISH_MATERIAL_CODE', $item['finish_material_code'])
                      ->where('SAP_PLANT', $item['sap_plant'])
                      ->set($payload)
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
            'message' => 'Material Master update completed successfully',
            'data' => $responseData
        ], 200);
    }
}
