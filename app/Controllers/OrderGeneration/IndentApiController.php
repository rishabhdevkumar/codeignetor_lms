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
                        "SCHEDULE_DATE" => $item['request_date'] ?? "",
                        "MATERIAL" => $finishMaterialData['FINISH_MATERIAL_CODE'] ?? "",
                        "UOM" => $finishMaterialData['UOM'] ?? "",
                        "DELIVERY PLANT" => $finishMaterialData['SAP_PLANT'] ?? "",
                        "INDENT LINE ITEM" => $item['line_item'] ?? "",
                        "QUANTITY IN KG" => $item['quantity'] ?? 0,
                        "DELIVERY_DATE" => $indentAlloted['DOOR_STEP_DEL_DATE'] ?? "",
                        "REQUIRED DATE" => $indentAlloted['TO_DATE'] ?? ""
                    ];
                }, $items);

                $result[] = [
                    "INDENT DATE" => $row['in_date'] ?? "",
                    "INDENT NO" => $row['in_no'] ?? "",
                    "DEALER CODE" => $row['sold_to_code'] ?? "",
                    "BILL TO CODE" => $row['bill_to_code'] ?? "",
                    "SHIP TO CODE" => $row['ship_to_code'] ?? "",
                    "MARKET SEGMENT" => $row['market_segment'] ?? "",
                    "ORDER TYPE" => $row['order_type'] ?? "",
                    "DEALER_PO_NO" => $row['po_no'] ?? "",
                    "ITEMDETAILS" => $formattedItems
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
}
