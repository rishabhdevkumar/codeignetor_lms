<?php

namespace App\Controllers\IndentAllotment;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductionPlanning\PlanningProductionModel;
use App\Models\MasterModels\PpCustomerMaster;
use App\Models\Material_Model;
use App\Models\MRMaterial_Model;

class ScheduleDateApi extends ResourceController
{
    protected $planningModel;
    protected $ppCustomerMaster;
    protected $materialModel;
    protected $mrMaterialModel;

    public function __construct()
    {
        $this->planningModel = new PlanningProductionModel();
        $this->ppCustomerMaster = new PpCustomerMaster();
        $this->materialModel = new Material_Model();
        $this->mrMaterialModel = new MRMaterial_Model();
    }

    public function getScheduleDetails()
    {
        // Only allow POST
        if ($this->request->getMethod() !== 'post') {
            return $this->fail('Method Not Allowed', 405);
        }

        // Get JSON body
        $json = $this->request->getJSON(true);

        if (!$json) {
            return $this->fail('Invalid JSON input', 400);
        }

        $materialcode = $json['materialcode'] ?? null;
        $grade        = $json['grade'] ?? null;
        $gsm          = $json['gsm'] ?? null;
        $customerno   = $json['customerno'] ?? null;
        $width        = $json['width'] ?? null;
        $length       = $json['length'] ?? null;
        $quantity     = $json['quantity'] ?? null;


        // Validation
        if (!$grade || !$gsm || !$customerno) {
            return $this->failValidationErrors('Required fields missing');
        }

        if (!is_numeric($gsm) || !is_numeric($quantity)) {
            return $this->failValidationErrors('gsm, quantity must be numeric');
        }

        $customerType = $this->ppCustomerMaster
            ->select('CUSTOMER_TYPE')
            ->where('CUSTOMER_CODE', $customerno)
            ->first();

        if (empty($customerType) || ($customerType['CUSTOMER_TYPE'] !== 'KC1' && $customerType['CUSTOMER_TYPE'] !== 'KC2')) {
            $customerType['CUSTOMER_TYPE'] = 'NKC';
        }

        if (!empty($materialcode && $materialcode !== null)) {

            $material = $this->materialModel
                ->select('ID, FINISH_MATERIAL_CODE, MR_MATERIAL_CODE, PACKAGING_TIME')
                ->where('FINISH_MATERIAL_CODE', $materialcode)
                ->first();

            $mrmaterialcode = $material['MR_MATERIAL_CODE'] ?? null;

        } else {
            $MRmaterial = $this->mrMaterialModel
                ->select('PP_ID, MR_MATERIAL_CODE')
                ->where('GRADE', $grade)
                ->where('GSM', $gsm)
                ->first();

            $mrmaterialcode = $MRmaterial['MR_MATERIAL_CODE'] ?? null;
        }

        $finishmaterialcode = $material['FINISH_MATERIAL_CODE'] ?? null;
        $packagingdays = (int) 5;
        $currentDateTime = date('Y-m-d H:i:s');
        $customerTypeBalanceQtyField = $customerType['CUSTOMER_TYPE'] . "_BALANCE_QTY_MT";

        $materialCodes = array_filter([$mrmaterialcode, $finishmaterialcode]);

        $baseQuery = $this->planningModel
            ->select('PP_ID, FROM_DATE_TIME, TO_DATE_TIME')
            ->where('TO_DATE_TIME >', $currentDateTime)
            ->where('REALLOCATION_STATUS', 0)
            ->where($customerTypeBalanceQtyField . ' >=', $quantity);


        $planningData = [];

        if (!empty($mrmaterialcode)) {
            $planningData = $baseQuery
                ->whereIn('SAP_MR_FG_CODE', $materialCodes)
                ->orderBy('FROM_DATE_TIME', 'ASC')
                ->first();
        }

        if (!empty($planningData)) {
            $earliestDate = $planningData['TO_DATE_TIME'];
            $finalFinishingDate = new \DateTime($earliestDate);
            $finalFinishingDate->add(new \DateInterval("P{$packagingdays}D"));
            $formattedFinishingDate = $finalFinishingDate->format('d-m-Y');
            $message = "Success";
            $status = true;
        } else {
            $formattedFinishingDate = null;
            $message = "Planning Slot Not Found";
            $status = false;
        }

        return $this->respond([
            'status' => $status,
            'message' => $message,
            'data' => [
                'finishing_date'      => $formattedFinishingDate,
            ]
        ], 200);
    }
}
