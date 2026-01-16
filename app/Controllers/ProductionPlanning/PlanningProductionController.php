<?php

namespace App\Controllers\ProductionPlanning;

use App\Controllers\BaseController;
use App\Models\ProductionPlanning\PlanningProductionModel;
use App\Models\ProductionPlanning\PpCalendarApprovalModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PlanningProductionController extends BaseController
{
    protected $model;
    protected $calendarApprovalModel;

    public function __construct()
    {
        $this->model = new PlanningProductionModel();
        $this->calendarApprovalModel = new PpCalendarApprovalModel();
    }

    /* -------------------------------------------------------------
        LIST ALL RECORDS
    ------------------------------------------------------------- */
    public function index()
    {

        $data['records'] = $this->model->select('pp_production_planning_master.*, pp_machine_master.MACHINE_TPM_ID, pp_mr_material_master.MR_MATERIAL_CODE')
            ->join('pp_machine_master', 'pp_machine_master.PP_ID = pp_production_planning_master.MACHINE')
            ->join('pp_mr_material_master', 'pp_mr_material_master.PP_ID = pp_production_planning_master.SAP_MOTHER_ROLL_CODE')
            ->findAll();
        return view('ProductionPlanning/index', $data);
    }

    public function calendarView()
    {

        $data['records'] = $this->model->select('pp_production_planning_master.*, pp_machine_master.MACHINE_TPM_ID, pp_mr_material_master.MR_MATERIAL_CODE, pp_mr_material_master.GRADE, pp_mr_material_master.GSM')
            ->join('pp_machine_master', 'pp_machine_master.PP_ID = pp_production_planning_master.MACHINE')
            ->join('pp_mr_material_master', 'pp_mr_material_master.PP_ID = pp_production_planning_master.SAP_MOTHER_ROLL_CODE')
            ->findAll();

        return view('ProductionPlanning/calendarView', $data);
    }

    /* -------------------------------------------------------------
        CREATE FORM
    ------------------------------------------------------------- */
    public function create()
    {
        return view('ProductionPlanning/create');
    }

    /* -------------------------------------------------------------
        SAVE NEW RECORD
    ------------------------------------------------------------- */
    public function store()
    {
        $this->model->save([
            'VERSION' => $this->request->getPost('VERSION'),
            'MACHINE' => $this->request->getPost('MACHINE'),
            'SAP_MR_FG_CODE' => $this->request->getPost('SAP_MOTHER_ROLL_CODE'),
            'QTY_MT' => $this->request->getPost('QTY_MT'),
            'FROM_DATE_TIME' => $this->request->getPost('FROM_DATE_TIME'),
            'TO_DATE_TIME' => $this->request->getPost('TO_DATE_TIME'),
            'UTILISED_QTY' => $this->request->getPost('UTILISED_QTY'),
            'BALANCE_QTY' => $this->request->getPost('BALANCE_QTY'),
            'KC1_QTY_MT' => $this->request->getPost('KC1_QTY_MT'),
            'KC2_QTY_MT' => $this->request->getPost('KC2_QTY_MT'),
            'NKC_QTY_MT' => $this->request->getPost('NKC_QTY_MT'),
            'KC1_UTILISED_QTY_MT' => $this->request->getPost('KC1_UTILISED_QTY_MT'),
            'KC2_UTILISED_QTY_MT' => $this->request->getPost('KC2_UTILISED_QTY_MT'),
            'NKC_UTILISED_QTY_MT' => $this->request->getPost('NKC_UTILISED_QTY_MT'),
            'KC1_BALANCE_QTY_MT' => $this->request->getPost('KC1_BALANCE_QTY_MT'),
            'KC2_BALANCE_QTY_MT' => $this->request->getPost('KC2_BALANCE_QTY_MT'),
            'NKC_BALANCE_QTY_MT' => $this->request->getPost('NKC_BALANCE_QTY_MT'),
            'UPLOADED_BY' => $this->request->getPost('UPLOADED_BY'),
            'UPLOADED_DATE' => $this->request->getPost('UPLOADED_DATE')
        ]);

        return redirect()->to('/planning-production')->with('success', 'Record added successfully');
    }

    /* -------------------------------------------------------------
        EDIT FORM
    ------------------------------------------------------------- */
    public function edit($id)
    {
        $data['record'] = $this->model->find($id);

        if (!$data['record']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Record not found");
        }

        return view('ProductionPlanning/edit', $data);
    }

    /* -------------------------------------------------------------
        UPDATE RECORD
    ------------------------------------------------------------- */
    public function update($id)
    {
        $this->model->update($id, [
            'VERSION' => $this->request->getPost('VERSION'),
            'MACHINE' => $this->request->getPost('MACHINE'),
            'SAP_MR_FG_CODE' => $this->request->getPost('SAP_MOTHER_ROLL_CODE'),
            'QTY_MT' => $this->request->getPost('QTY_MT'),
            'FROM_DATE_TIME' => $this->request->getPost('FROM_DATE_TIME'),
            'TO_DATE_TIME' => $this->request->getPost('TO_DATE_TIME'),
            'UTILISED_QTY' => $this->request->getPost('UTILISED_QTY'),
            'BALANCE_QTY' => $this->request->getPost('BALANCE_QTY'),
            'KC1_QTY_MT' => $this->request->getPost('KC1_QTY_MT'),
            'KC2_QTY_MT' => $this->request->getPost('KC2_QTY_MT'),
            'NKC_QTY_MT' => $this->request->getPost('NKC_QTY_MT'),
            'KC1_UTILISED_QTY_MT' => $this->request->getPost('KC1_UTILISED_QTY_MT'),
            'KC2_UTILISED_QTY_MT' => $this->request->getPost('KC2_UTILISED_QTY_MT'),
            'NKC_UTILISED_QTY_MT' => $this->request->getPost('NKC_UTILISED_QTY_MT'),
            'KC1_BALANCE_QTY_MT' => $this->request->getPost('KC1_BALANCE_QTY_MT'),
            'KC2_BALANCE_QTY_MT' => $this->request->getPost('KC2_BALANCE_QTY_MT'),
            'NKC_BALANCE_QTY_MT' => $this->request->getPost('NKC_BALANCE_QTY_MT'),
            'UPLOADED_BY' => $this->request->getPost('UPLOADED_BY'),
            'UPLOADED_DATE' => $this->request->getPost('UPLOADED_DATE')
        ]);

        return redirect()->to('/planning-production')->with('success', 'Record updated successfully');
    }

    /* -------------------------------------------------------------
        DELETE RECORD
    ------------------------------------------------------------- */
    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/planning-production')->with('success', 'Record deleted successfully');
    }

    public function uploadXlsx()
    {
        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {

            try {

                $spreadsheet = IOFactory::load($file->getTempName());
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                // Sort by first column (machine TPM ID)
                usort($rows, function ($a, $b) {
                    return $a[0] <=> $b[0];
                });

                $db = \Config\Database::connect();

                // First production of each day starts here
                $initialStartDateTime = new \DateTime('tomorrow 00:00:00');

                // Variables to store previous machine grade/gsm
                $prevGrade = null;
                $prevGsm = null;
                $prevMachineId = null;

                // This will change as production progresses
                $startDateTime = clone $initialStartDateTime;

                for ($i = 0; $i < count($rows); $i++) {

                    $row = $rows[$i];

                    // Skip header or empty rows
                    if ($i == 0 || empty($row[0]))
                        continue;

                    $machineTpmId = $row[0];
                    $machineMaterialCode = $row[1];
                    $plannedQty = $row[2];

                    // Fetch machine master
                    $machine = $db->table('pp_machine_master')
                        ->where('MACHINE_TPM_ID', $machineTpmId)
                        ->get()
                        ->getRowArray();

                    if (!$machine) {
                        return redirect()->back()->with('error', 'Machine not found for ' . $machineTpmId);
                    }

                    $machinePPId = $machine['PP_ID'];
                    $gsmChangeTime = $machine['GSM_CHANGE_TIME_MIN'];
                    $gradeChangeTime = $machine['GRADE_CHANGE_TIME_MIN'];
                    $sapplant = $machine['SAP_PLANT'];

                   
                    // Fetch mother roll
                    $motherRoll = $db->table('pp_mr_material_master')
                        ->where('MR_MATERIAL_CODE', $machineMaterialCode)
                        ->where('SAP_PLANT', $sapplant)
                        ->get()
                        ->getRowArray();

                    if (!$motherRoll) {
                        return redirect()->back()->with(
                            'error',
                            "Material not found: $machineMaterialCode (Plant: $sapplant)"
                        );
                    }

                    $motherRollPPId = $motherRoll['PP_ID'];
                    $machineOutputKgHr = $motherRoll['MACHINE_OUTPUT_KG_HR'];
                    $grade = $motherRoll['GRADE'];
                    $gsm = $motherRoll['GSM'];

                    // ----------------------------
                    // MACHINE CHANGE LOGIC
                    // ----------------------------
                    $gradeChanged = false;
                    $gsmChanged = false;

                    if ($prevMachineId === null) {
                        // First row → no comparison
                    } elseif ($prevMachineId !== $machinePPId) {

                        // Machine changed → reset clock
                        $startDateTime = clone $initialStartDateTime;
                    } else {
                        // Same machine → compare grade/gsm
                        $gradeChanged = ($grade !== $prevGrade);
                        $gsmChanged = ($gsm !== $prevGsm);
                    }

                    // Additional time for machine changeover
                    $additionalMinutes = 0;
                    if ($gradeChanged) {
                        $additionalMinutes += $gradeChangeTime;
                    } elseif ($gsmChanged) {
                        $additionalMinutes += $gsmChangeTime;
                    }

                    // --------------------------------------
                    // APPLY ADDITIONAL MINUTES TO FROM_TIME
                    // --------------------------------------
                    $fromDateTime = clone $startDateTime;
                    if ($additionalMinutes > 0) {
                        $fromDateTime->modify("+{$additionalMinutes} minutes");
                    }

                    // --------------------------------------
                    // PRODUCTION TIME CALCULATION
                    // --------------------------------------
                    $plannedQtyKg = $plannedQty * 1000;
                    $productionHour = ($machineOutputKgHr > 0)
                        ? ($plannedQtyKg / $machineOutputKgHr)
                        : 0;

                    $hours = floor($productionHour);
                    $minutes = round(($productionHour - $hours) * 60);

                    // END TIME = FROM_TIME + PRODUCTION TIME
                    $toDateTime = clone $fromDateTime;
                    $toDateTime->modify("+{$hours} hours +{$minutes} minutes");

                    // Final string values
                    $fromDateTimeStr = $fromDateTime->format('Y-m-d H:i:s');
                    $toDateTimeStr = $toDateTime->format('Y-m-d H:i:s');

                    // Fetch quota
                    $qouta = $db->table('pp_customer_quota_master')
                        ->where('GRADE', $grade)
                        ->orderBy('CUSTOMER_TYPE', 'ASC')
                        ->get()
                        ->getResultArray();

                    $kc1Quota = $plannedQty * $qouta[0]['QUOTA_PERCENTAGE'] / 100;
                    $kc2Quota = $plannedQty * $qouta[1]['QUOTA_PERCENTAGE'] / 100;

                    $nkcQuota = $plannedQty - ($kc1Quota + $kc2Quota);

                    // Insert Final Row
                    $data = [
                        'VERSION' => 1,
                        'MACHINE' => $machinePPId,
                        'SAP_MR_FG_CODE' => $motherRollPPId,
                        'QTY_MT' => $plannedQty,
                        'BALANCE_QTY' => $plannedQty,
                        'KC1_QTY_MT' => $kc1Quota,
                        'KC2_QTY_MT' => $kc2Quota,
                        'NKC_QTY_MT' => $nkcQuota,
                        'KC1_BALANCE_QTY_MT' => $kc1Quota,
                        'KC2_BALANCE_QTY_MT' => $kc2Quota,
                        'NKC_BALANCE_QTY_MT' => $nkcQuota,
                        'FROM_DATE_TIME' => $fromDateTimeStr,
                        'TO_DATE_TIME' => $toDateTimeStr,
                        'CALENDAR_TYPE' => 'M',
                        'UPLOADED_BY' => '',
                        'UPLOADED_DATE' => date('Y-m-d H:i:s'),
                    ];

                    $db->table('pp_production_planning_master')->insert($data);

                    // NEXT ROW STARTS FROM CURRENT END TIME
                    $startDateTime = clone $toDateTime;
                    $prevGrade = $grade;
                    $prevGsm = $gsm;
                    $prevMachineId = $machinePPId;
                }

                return redirect()->back()->with('success', 'Excel uploaded & data inserted successfully!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error reading XLSX: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Invalid file.');
    }

    public function dragDropView()
    {

        $subQuery = $this->calendarApprovalModel
            ->select('PLANNING_CAL_ID')
            ->builder()
            ->getCompiledSelect();

        $data['items'] = $this->model
            ->where('FROM_DATE_TIME >=', date('Y-m-d 00:00:00'))
            ->where("PP_ID NOT IN ($subQuery)", null, false)
            ->orderBy('FROM_DATE_TIME', 'ASC')
            ->findAll();
        return view('ProductionPlanning/reorderProductionPlanning', $data);
    }


    public function updateProductionPlanningOrder()
    {
        $order = $this->request->getPost('order');

        if (empty($order)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid order data'
            ]);
        }

        $model = $this->model;
        $db = \Config\Database::connect();



        $db->transStart();

        $ppIds = [];
        foreach ($order as $row) {
            $ppIds[] = (int) $row['id'];
        }

        // Fetch rows in new order
        $rows = $model
            ->whereIn('PP_ID', $ppIds)
            ->orderBy("FIELD(PP_ID," . implode(',', $ppIds) . ")", '', false)
            ->findAll();


        if (empty($rows)) {
            return $this->response->setJSON(['status' => false]);
        }

        // Start from first row FROM_DATE_TIME
        $startDateTime = new \DateTime($rows[0]['FROM_DATE_TIME']);

        $prevGrade = null;
        $prevGsm = null;

        foreach ($rows as $index => $row) {

            // Fetch machine master
            $machine = $db->table('pp_machine_master')
                ->where('PP_ID', $row['MACHINE'])
                ->get()
                ->getRowArray();

            if (!$machine) {
                continue;
            }

            // Fetch material master
            $material = $db->table('pp_mr_material_master')
                ->where('MR_MATERIAL_CODE', $row['SAP_MR_FG_CODE'])
                ->where('SAP_PLANT', $machine['SAP_PLANT'])
                ->get()
                ->getRowArray();


            if (!$material) {
                continue;
            }

            // ---------------------------
            // CHANGEOVER TIME LOGIC
            // ---------------------------
            $additionalMinutes = 0;

            if ($index > 0) {
                if ($material['GRADE'] !== $prevGrade) {
                    $additionalMinutes += $machine['GRADE_CHANGE_TIME_MIN'];
                } elseif ($material['GSM'] !== $prevGsm) {
                    $additionalMinutes += $machine['GSM_CHANGE_TIME_MIN'];
                }
            }

            // Apply additional minutes
            $fromDateTime = clone $startDateTime;
            if ($additionalMinutes > 0) {
                $fromDateTime->modify("+{$additionalMinutes} minutes");
            }

            // ---------------------------
            // PRODUCTION TIME
            // ---------------------------
            $plannedQtyKg = $row['QTY_MT'] ;
            $outputKgHr = $material['MACHINE_OUTPUT_KG_HR'];

            $productionHours = ($outputKgHr > 0)
                ? ($plannedQtyKg / $outputKgHr)
                : 0;

            $hours = floor($productionHours);
            $minutes = round(($productionHours - $hours) * 60);

            $toDateTime = clone $fromDateTime;
            $toDateTime->modify("+{$hours} hours +{$minutes} minutes");


            // // ---------------------------
            // // UPDATE ROW
            // // ---------------------------
            // $model->update($row['PP_ID'], [
            //     'sort_order' => $index + 1,
            //     'FROM_DATE_TIME' => $fromDateTime->format('Y-m-d H:i:s'),
            //     'TO_DATE_TIME' => $toDateTime->format('Y-m-d H:i:s')
            // ]);

            $modifiedData = [
                'PLANNING_CAL_ID' => $row['PP_ID'],
                'VERSION' => $row['VERSION'] + 1,
                'MACHINE' => $row['MACHINE'],
                'SAP_MR_FG_CODE' => $row['SAP_MR_FG_CODE'],
                'QTY_MT' => $row['QTY_MT'],
                'BALANCE_QTY' => $row['BALANCE_QTY'],
                'KC1_QTY_MT' => $row['KC1_QTY_MT'],
                'APPROVAL_STATUS' => 'P',
                'KC2_QTY_MT' => $row['KC2_QTY_MT'],
                'KC1_BALANCE_QTY_MT' => $row['KC1_BALANCE_QTY_MT'],
                'KC2_BALANCE_QTY_MT' => $row['KC2_BALANCE_QTY_MT'],
                'FROM_DATE_TIME' => $fromDateTime->format('Y-m-d H:i:s'),
                'TO_DATE_TIME' => $toDateTime->format('Y-m-d H:i:s'),
                'UPLOADED_BY' => '',
                'UPLOADED_DATE' => date('Y-m-d H:i:s'),
            ];


            $db->table('pp_calendar_approval')->insert($modifiedData);

            // Prepare for next iteration
            $startDateTime = clone $toDateTime;
            $prevGrade = $material['GRADE'];
            $prevGsm = $material['GSM'];
        }

        $db->transComplete();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Production plan updated successfully'
        ]);
    }


    public function pushToCalendarApproval()
    {

        $planningModel = $this->model;
        $approvalModel = $this->calendarApprovalModel;
        $db = \Config\Database::connect();

        $db->transStart();

        // 1️⃣ Get already approved planning_calendar_ids
        $existingIds = $approvalModel
            ->select('PLANNING_CAL_ID')
            ->findColumn('PLANNING_CAL_ID');

        // 2️⃣ Fetch only non-existing PP_ID rows
        $planningRows = $planningModel
            ->when(
                !empty($existingIds),
                fn($q) => $q->whereNotIn('PP_ID', $existingIds)
            )
            ->findAll();

        if (!empty($planningRows)) {

            $insertData = [];

            foreach ($planningRows as $row) {
                $insertData[] = [
                    'PLANNING_CAL_ID' => $row['PP_ID'],
                    'VERSION' => $row['VERSION'],
                    'MACHINE' => $row['MACHINE'],
                    'SAP_MR_FG_CODE' => $row['SAP_MR_FG_CODE'],
                    'QTY_MT' => $row['QTY_MT'],
                    'FROM_DATE_TIME' => $row['FROM_DATE_TIME'],
                    'TO_DATE_TIME' => $row['TO_DATE_TIME'],
                    'UTILISED_QTY' => $row['UTILISED_QTY'],
                    'BALANCE_QTY' => $row['BALANCE_QTY'],
                    'KC1_QTY_MT' => $row['KC1_QTY_MT'],
                    'KC2_QTY_MT' => $row['KC2_QTY_MT'],
                    'NKC_QTY_MT' => $row['NKC_QTY_MT'],
                    'KC1_UTILISED_QTY_MT' => $row['KC1_UTILISED_QTY_MT'],
                    'KC2_UTILISED_QTY_MT' => $row['KC2_UTILISED_QTY_MT'],
                    'NKC_UTILISED_QTY_MT' => $row['NKC_UTILISED_QTY_MT'],
                    'KC1_BALANCE_QTY_MT' => $row['KC1_BALANCE_QTY_MT'],
                    'KC2_BALANCE_QTY_MT' => $row['KC2_BALANCE_QTY_MT'],
                    'NKC_BALANCE_QTY_MT' => $row['NKC_BALANCE_QTY_MT'],
                    'CALENDAR_TYPE' => $row['CALENDAR_TYPE'],
                    'APPROVAL_STATUS' => 'P',
                    'UPLOADED_BY' => $row['UPLOADED_BY'],
                    'UPLOADED_DATE' => $row['UPLOADED_DATE'],
                    'ACTION_BY' => null,
                    'ACTION_DATE' => null
                ];
            }

            // 3️⃣ Batch insert
            $approvalModel->insertBatch($insertData);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to push data to approval'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'inserted_rows' => count($planningRows),
            'message' => 'Data pushed to approval table successfully'
        ]);
    }

    public function insertSingleProductionPlanning()
    {
        try {

            $db = \Config\Database::connect();

            // -----------------------------
            // FORM DATA
            // -----------------------------
            $machineTpmId = $this->request->getPost('machine');
            $machineMaterialCode = $this->request->getPost('sap_mother_roll_code');
            $plannedQty = (float) $this->request->getPost('qty_mt');


            if (!$machineTpmId || !$machineMaterialCode || $plannedQty <= 0) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Invalid input data'
                ]);
            }

            // -----------------------------
            // FETCH MACHINE MASTER
            // -----------------------------
            $machine = $db->table('pp_machine_master')
                ->where('MACHINE_TPM_ID', $machineTpmId)
                ->get()
                ->getRowArray();


            if (!$machine) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Machine not found: {$machineTpmId}"
                ]);
            }

            $machinePPId = $machine['PP_ID'];
            $gsmChangeTime = $machine['GSM_CHANGE_TIME_MIN'];
            $gradeChangeTime = $machine['GRADE_CHANGE_TIME_MIN'];
            $sapplant = $machine['SAP_PLANT'];

            // -----------------------------
            // FETCH MOTHER ROLL
            // -----------------------------
            $motherRoll = $db->table('pp_mr_material_master')
                ->where('MR_MATERIAL_CODE', $machineMaterialCode)
                ->where('SAP_PLANT', $sapplant)
                ->get()
                ->getRowArray();

            if (!$motherRoll) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Material not found: {$machineMaterialCode}"
                ]);
            }

            $motherRollPPId = $motherRoll['PP_ID'];
            $machineOutputKgHr = $motherRoll['MACHINE_OUTPUT_KG_HR'];
            $grade = $motherRoll['GRADE'];
            $gsm = $motherRoll['GSM'];

            // -----------------------------
            // GET LAST PRODUCTION FOR MACHINE
            // -----------------------------
            $lastProduction = $db->table('pp_calendar_approval')
                ->where('MACHINE', $machinePPId)
                ->orderBy('TO_DATE_TIME', 'DESC')
                ->get()
                ->getRowArray();

            // First production of the day
            $initialStartDateTime = new \DateTime('tomorrow 00:00:00');

            if ($lastProduction) {
                $startDateTime = new \DateTime($lastProduction['TO_DATE_TIME']);
                $prevGrade = $lastProduction['GRADE'] ?? null;
                $prevGsm = $lastProduction['GSM'] ?? null;
            } else {
                $startDateTime = clone $initialStartDateTime;
                $prevGrade = $prevGsm = null;
            }

            // -----------------------------
            // CHANGEOVER LOGIC
            // -----------------------------
            $additionalMinutes = 0;

            if ($prevGrade !== null) {
                if ($grade !== $prevGrade) {
                    $additionalMinutes += $gradeChangeTime;
                } elseif ($gsm !== $prevGsm) {
                    $additionalMinutes += $gsmChangeTime;
                }
            }

            $fromDateTime = clone $startDateTime;
            if ($additionalMinutes > 0) {
                $fromDateTime->modify("+{$additionalMinutes} minutes");
            }

            // -----------------------------
            // PRODUCTION TIME CALCULATION
            // -----------------------------
            $plannedQtyKg = $plannedQty;
            $productionHr = ($machineOutputKgHr > 0)
                ? ($plannedQtyKg / $machineOutputKgHr)
                : 0;

            $hours = floor($productionHr);
            $minutes = round(($productionHr - $hours) * 60);

            $toDateTime = clone $fromDateTime;
            $toDateTime->modify("+{$hours} hours +{$minutes} minutes");

            // -----------------------------
            // QUOTA SPLIT
            // -----------------------------
            $quota = $db->table('pp_customer_quota_master')
                ->where('GRADE', $grade)
                ->orderBy('CUSTOMER_TYPE', 'ASC')
                ->get()
                ->getResultArray();

            $kc1Qty = $plannedQty * $quota[0]['QUOTA_PERCENTAGE'] / 100;
            $kc2Qty = $plannedQty * $quota[1]['QUOTA_PERCENTAGE'] / 100;

            // -----------------------------
            // INSERT DATA
            // -----------------------------
            $data = [
                'VERSION' => 1,
                'MACHINE' => $machinePPId,
                'SAP_MR_FG_CODE' => $motherRollPPId,
                'QTY_MT' => $plannedQty,
                'BALANCE_QTY' => $plannedQty,
                'KC1_QTY_MT' => $kc1Qty,
                'APPROVAL_STATUS' => 'P',
                'KC2_QTY_MT' => $kc2Qty,
                'KC1_BALANCE_QTY_MT' => $kc1Qty,
                'KC2_BALANCE_QTY_MT' => $kc2Qty,
                'FROM_DATE_TIME' => $fromDateTime->format('Y-m-d H:i:s'),
                'TO_DATE_TIME' => $toDateTime->format('Y-m-d H:i:s'),
                'UPLOADED_BY' => '',
                'UPLOADED_DATE' => date('Y-m-d H:i:s'),
            ];


            $db->table('pp_calendar_approval')->insert($data);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Production planning inserted successfully'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


}
