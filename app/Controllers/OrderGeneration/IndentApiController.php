<?php

namespace App\Controllers\OrderGeneration;

use CodeIgniter\RESTful\ResourceController;
use App\Models\OrderGeneration\IndentModel;
use App\Models\OrderGeneration\IndentDetailsModel;
use App\Models\OrderGeneration\IndentAllotmentModel;
use App\Models\Material\MaterialModel;


class IndentApiController extends ResourceController
{
    // POST API method
    public function updateSapDetails()
    {

        // Read JSON payload
        $data = $this->request->getJSON(true);


        if (!$data || !is_array($data)) {
            return $this->fail('Invalid JSON payload');
        }

        $model = new IndentAllotmentModel();
        $responseData = [];

        foreach ($data as $item) {

            if (!isset($item['indentno']) || !isset($item['saporderno']) || !isset($item['sapremarks'])) {
                return $this->fail('Missing required fields');
            }

            // Update database
            $update = [
                'SAP_ORDER_NO' => $item['saporderno'],
                'SAP_REMARKS' => $item['sapremarks'],
            ];

            $model->where('INDENT_NO', $item['indentno'])
                ->set($update)
                ->update();

            $responseData[] = [
                'indentno' => $item['indentno'],
                'status' => 'Updated Successfully'
            ];
        }

        return $this->respond([
            'status' => true,
            'message' => 'SAP details updated successfully',
            'data' => $responseData
        ], 200);

    }

    public function getIndentSummary()
    {
        try {
            $indentModel = new IndentModel();
            $itemModel = new IndentDetailsModel();
            $indentAllotmentModel = new IndentAllotmentModel();
            $finishMaterialMaster = new MaterialModel();

            $nonAllotedIndents = $indentAllotmentModel
                ->select('INDENT_NO')
                ->distinct()
                ->groupStart()
                ->where('SAP_ORDER_NO', NULL)
                ->orWhere('SAP_ORDER_NO', '')
                ->groupEnd()
                ->findAll();


            $indentNos = array_column($nonAllotedIndents, 'INDENT_NO');
            $indents = $indentModel
                ->whereIn('in_no', $indentNos)
                ->findAll();

            if (empty($indents)) {
                return $this->respond([
                    "ApiMessage" => "No indents found.",
                    "ApiData" => [],
                    "IsSuccessful" => false
                ], 404);
            }

            $indentNos = array_column($indents, 'in_no');

            // Fetch all items for these indents
            $itemsData = $itemModel->whereIn('in_no', $indentNos)->findAll();
            $itemsGrouped = [];
            foreach ($itemsData as $item) {
                $itemsGrouped[$item['in_no']][] = $item;
            }

            $allotmentsData = $indentAllotmentModel->whereIn('INDENT_NO', $indentNos)->findAll();
            $allotmentsGrouped = [];
            foreach ($allotmentsData as $allot) {
                $allotmentsGrouped[$allot['INDENT_NO']] = $allot;
            }

            $result = [];

            foreach ($indents as $row) {
                $indentNo = $row['in_no'];
                $indentAlloted = $allotmentsGrouped[$indentNo] ?? [];

                $items = $itemsGrouped[$indentNo] ?? [];
                // echo "<pre>";print_R($items);die();

                $formattedItems = array_map(function ($item) use ($indentAlloted, $finishMaterialMaster) {
                    $finishMaterialData = $finishMaterialMaster->where('FINISH_MATERIAL_CODE', $indentAlloted['FINISH_MATERIAL_CODE'])->first();
                    return [
                        "ITEM_TYPE" => $item['item_type'] ?? "",
                        "GRADE" => $item['item_variety'] ?? "",
                        "GSM" => $item['gsm'] ?? "",
                        "SCHEDULE_DATE" => $indentAlloted['TO_DATE'] ?? "",
                        "MATERIAL" => $finishMaterialData['FINISH_MATERIAL_CODE'] ?? "",
                        "UOM" => $finishMaterialData['UOM'] ?? "",
                        "DELIVERY_PLANT" => $finishMaterialData['SAP_PLANT'] ?? "",
                        "INDENT_LINE_ITEM" => $item['line_item'] ?? "",
                        "QUANTITY_IN_KG" => $item['quantity'] ?? 0,
                        "DELIVERY_DATE" => $indentAlloted['DOOR_STEP_DEL_DATE'] ?? "",
                        "REQUIRED_DATE" => $item['request_date'] ?? "",
                        "remarks" => $item['remarks'] ?? ""
                    ];
                }, $items);

                $result[] = [
                    "INDENT_DATE" => $row['in_date'] ?? "",
                    "INDENT_NO" => $row['in_no'] ?? "",
                    "DEALER_CODE" => $row['sold_to_code'] ?? "",
                    "BILL_TO_CODE" => $row['bill_to_code'] ?? "",
                    "SHIP_TO_CODE" => $row['ship_to_code'] ?? "",
                    "MARKET_SEGMENT" => $row['market_segment'] ?? "",
                    "ORDER_TYPE" => $row['order_type'] ?? "",
                    "DEALER_PO_NO" => $row['po_no'] ?? "",
                    "remarks" => $row['remarks'] ?? "",
                    "ITEMSDETAILS" => $formattedItems
                ];
            }

            return $this->respond([
                "ApiMessage" => "Data found!",
                "ApiData" => $result,
                "IsSuccessful" => true
            ], 200);

        } catch (\Exception $e) {
            return $this->respond([
                "ApiMessage" => "An error occurred: " . $e->getMessage(),
                "ApiData" => [],
                "IsSuccessful" => false
            ], 500);
        }
    }

    public function getChangedIndent()
    {
        try {
            $indentAllotmentModel = new IndentAllotmentModel();

            $nonAllotedIndents = $indentAllotmentModel
                ->distinct()
                ->groupStart()
                ->where('SAP_ORDER_NO IS NOT NULL', null, false)
                ->where('SAP_ORDER_NO !=', '')
                ->groupEnd()
                ->where('MODIFICATION_FLAG', 'X')
                ->where('SAP_ORDER_CHANGE', false)
                ->findAll();


            if (empty($nonAllotedIndents)) {
                return $this->respond([
                    "ApiMessage" => "No Changed Indents Found.",
                    "ApiData" => [],
                    "IsSuccessful" => false
                ], 404);
            }


            $result = [];

            foreach ($nonAllotedIndents as $row) {

                $result[] = [
                    "INDENT_NO" => $row['INDENT_NO'] ?? "",  
                    "INDENT_LINE_ITEM" => $row['INDENT_LINE_ITEM'] ?? "", 
                    "SCHEDULE_DATE" => $row['TO_DATE'] ?? "",
                    "DELIVERY_DATE" => $row['DOOR_STEP_DEL_DATE'] ?? "",
                    "SAP_ORDER_NO" => $row['SAP_ORDER_NO'] ?? "",
                ];
            }

            return $this->respond([
                "ApiMessage" => "Data Found!",
                "ApiData" => $result,
                "IsSuccessful" => true
            ], 200);

        } catch (\Exception $e) {
            return $this->respond([
                "ApiMessage" => "An Error Occurred: " . $e->getMessage(),
                "ApiData" => [],
                "IsSuccessful" => false
            ], 500);
        }
    }

    public function updateChangedSapDetails()
    {

        // Read JSON payload
        $data = $this->request->getJSON(true);


        if (!$data || !is_array($data)) {
            return $this->fail('Invalid JSON payload');
        }

        $model = new IndentAllotmentModel();
        $responseData = [];

        foreach ($data as $item) {

            if (!isset($item['indentno']) || !isset($item['saporderno']) || !isset($item['sapremarks'])) {
                return $this->fail('Missing required fields');
            }

            // Update database
            $update = [
                'SAP_ORDER_CHANGE' => true,
                'SAP_REMARKS' => $item['sapremarks'],
            ];

            $model->where('INDENT_NO', $item['indentno'])
                  ->where('INDENT_NO', $item['indentlineitem'])
                  ->set($update)
                  ->update();

            $responseData[] = [
                'indentno' => $item['indentno'],
                'status' => 'Updated Successfully'
            ];
        }

        return $this->respond([
            'status' => true,
            'message' => 'SAP details updated successfully',
            'data' => $responseData
        ], 200);

    }

}
