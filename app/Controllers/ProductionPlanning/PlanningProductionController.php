<?php

namespace App\Controllers\ProductionPlanning;

use App\Controllers\BaseController;
use App\Models\ProductionPlanning\PlanningProductionModel;
use App\Models\ProductionPlanning\PpCalendarApprovalModel;
use App\Models\ProductionPlanning\PlanningProductionHistoryModel;
use App\Models\OrderGeneration\IndentAllotmentModel;
use App\Models\MasterModels\TransitMaster;
use App\Models\Material_Model;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PlanningProductionController extends BaseController
{
    protected $model;
    protected $calendarApprovalModel;
    protected $planningCalhistoryModel;
    protected $indentAllotment;

    protected $materialModel;
    protected $transitMaster;

    public function __construct()
    {
        $this->model = new PlanningProductionModel();
        $this->calendarApprovalModel = new PpCalendarApprovalModel();
        $this->planningCalhistoryModel = new PlanningProductionHistoryModel();
        $this->indentAllotment = new IndentAllotmentModel();
        $this->transitMaster = new TransitMaster();
        $this->materialModel = new Material_Model();
    }

    /* -------------------------------------------------------------
        LIST ALL RECORDS
    ------------------------------------------------------------- */
    public function index()
    {

        $data['records'] = $this->model->select('pp_production_planning_master.*, pp_machine_master.MACHINE_TPM_ID, pp_mr_material_master.MR_MATERIAL_CODE')
            ->join('pp_machine_master', 'pp_machine_master.PP_ID = pp_production_planning_master.MACHINE')
            ->join('pp_mr_material_master', 'pp_mr_material_master.MR_MATERIAL_CODE = pp_production_planning_master.SAP_MR_FG_CODE')
            ->findAll();

        $data['title'] = 'Planning Calendar';

        echo view('header', $data);
        echo view('ProductionPlanning/index', $data);
        echo view('footer');
    }

    public function calendarView()
    {

        $data['records'] = $this->model->select('pp_production_planning_master.*, pp_machine_master.MACHINE_TPM_ID, pp_mr_material_master.MR_MATERIAL_CODE, pp_mr_material_master.GRADE, pp_mr_material_master.GSM')
            ->join('pp_machine_master', 'pp_machine_master.PP_ID = pp_production_planning_master.MACHINE')
            ->join('pp_mr_material_master', 'pp_mr_material_master.MR_MATERIAL_CODE = pp_production_planning_master.SAP_MR_FG_CODE')
            ->findAll();

        $data['title'] = 'Planning Calendar';

        echo view('header', $data);
        echo view('ProductionPlanning/calendarView', $data);
        echo view('footer');
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
            'UPLOADED_DATE' => $this->request->getPost('UPLOADED_DATE'),
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

                $rows = array_filter($rows, function ($row2) {
                    return array_filter($row2) !== [];
                });

                $rows = array_values($rows);

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

                    // previous planning latest date time
                    $latestPlan = $db->table('pp_production_planning_master')
                        ->select('*')
                        ->where('MACHINE', $machinePPId)
                        ->orderBy('TO_DATE_TIME', 'DESC')
                        ->limit(1)
                        ->get()
                        ->getRowArray();

                    if ($latestPlan && !empty($latestPlan['TO_DATE_TIME'])) {
                        $startDateTime = new \DateTime($latestPlan['TO_DATE_TIME']);

                        // Fetch latest mother roll
                        $latestmotherRoll = $db->table('pp_mr_material_master')
                            ->where('MR_MATERIAL_CODE', $latestPlan['SAP_MR_FG_CODE'])
                            ->where('SAP_PLANT', $sapplant)
                            ->get()
                            ->getRowArray();

                        $prevGrade = $latestmotherRoll['GRADE'];
                        $prevGsm = $latestmotherRoll['GSM'];
                        
                    } else {
                        // No previous planning → use initial start time
                        $startDateTime = clone $initialStartDateTime;
                    }


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

                    $fromDateTime = clone $startDateTime;
                    if ($additionalMinutes > 0) {
                        $fromDateTime->modify("+{$additionalMinutes} minutes");
                    }

                    $plannedQtyKg = $plannedQty;
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
                    $quota = $db->table('pp_customer_quota_master')
                        ->where('GRADE', $grade)
                        ->orderBy('CUSTOMER_TYPE', 'ASC')
                        ->get()
                        ->getResultArray();

                    // if ($quota) {
                    //     $kc1Quota = $plannedQty * $quota[0]['QUOTA_PERCENTAGE'] / 100;
                    //     $kc2Quota = $plannedQty * $quota[1]['QUOTA_PERCENTAGE'] / 100;
                    // } else {
                    //     $kc1Quota = 0;
                    //     $kc2Quota = 0;
                    // }

                    $kc1Quota = 0;
                    $kc2Quota = 0;

                    if (is_array($quota)) {
                        if (!empty($quota[0]['QUOTA_PERCENTAGE'])) {
                            $kc1Quota = $plannedQty * $quota[0]['QUOTA_PERCENTAGE'] / 100;
                        }

                        if (!empty($quota[1]['QUOTA_PERCENTAGE'])) {
                            $kc2Quota = $plannedQty * $quota[1]['QUOTA_PERCENTAGE'] / 100;
                        }
                    }


                    $nkcQuota = $plannedQty - ($kc1Quota + $kc2Quota);

                    // Insert Final Row
                    $data = [
                        'VERSION' => 1,
                        'MACHINE' => $machinePPId,
                        'SAP_MR_FG_CODE' => $machineMaterialCode,
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

        $pendingMachines = $this->calendarApprovalModel
            ->select('MACHINE')
            ->where('APPROVAL_STATUS', 'P')
            ->groupBy('MACHINE')
            ->findAll();

        $pendingMachineList = array_column($pendingMachines, 'MACHINE');

        if (!empty($pendingMachineList)) {
            $this->model->whereNotIn('MACHINE', $pendingMachineList);
        }

        $data['items'] = $this->model
            // ->where('FROM_DATE_TIME >=', date('Y-m-d 00:00:00'))
            ->orderBy('FROM_DATE_TIME', 'ASC')
            ->findAll();

        $data['pendingApprovalMachines'] = array_fill_keys($pendingMachineList, true);

        $data['title'] = 'Drag & Drop';

        echo view('header', $data);
        echo view('ProductionPlanning/reorderProductionPlanning', $data);
        // echo view('footer');
    }

    public function planningApprovalView()
    {

        $data['originalPlans'] = $this->model
            ->orderBy('MACHINE', 'ASC')
            ->orderBy('FROM_DATE_TIME', 'ASC')
            ->findAll();

        $data['pendingReorders'] = $this->calendarApprovalModel
            ->where('APPROVAL_STATUS', 'P') // Pending
            ->orderBy('MACHINE', 'ASC')
            ->orderBy('FROM_DATE_TIME', 'ASC')
            ->findAll();

        $data['title'] = 'Planning Approval';

        echo view('header', $data);
        echo view('ProductionPlanning/planningApprovalView', $data);
        echo view('footer');
    }

    public function rejectPendingApproval()
    {
        $machine = $this->request->getPost('machine');

        if (!$machine) {
            return redirect()->back()->with('error', 'Machine not selected');
        }

        $this->calendarApprovalModel
            ->where('APPROVAL_STATUS', 'P')
            ->where('MACHINE', $machine)
            ->set(['APPROVAL_STATUS' => 'R'])
            ->update();
    }

    public function approvePendingApproval()
    {
        $planningCalIds = $this->request->getPost('planning_cal_ids') ?? [];
        $newRowIds = $this->request->getPost('new_row_ids') ?? [];

        if (empty($planningCalIds) && empty($newRowIds)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No planning records received'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {


            // CASE 1 : EXISTING PLANNING (PLANNING_CAL_ID > 0)
            foreach ($planningCalIds as $planningCalId) {

                $oldPlanning = $this->model
                    ->where('PP_ID', $planningCalId)
                    ->first();

                if (!$oldPlanning) {
                    continue;
                }

                $newPlanning = $this->calendarApprovalModel
                    ->where('PLANNING_CAL_ID', $planningCalId)
                    ->where('APPROVAL_STATUS', 'P')
                    ->first();

                if (!$newPlanning) {
                    continue;
                }

                // Save history
                $oldPlanning['PLANNING_CAL_ID'] = $oldPlanning['PP_ID'];
                $this->planningCalhistoryModel->insert($oldPlanning);

                // Update master
                $this->model->update(
                    $oldPlanning['PP_ID'],
                    [
                        'VERSION'        => $newPlanning['VERSION'],
                        'FROM_DATE_TIME' => $newPlanning['FROM_DATE_TIME'],
                        'TO_DATE_TIME'   => $newPlanning['TO_DATE_TIME'],
                        'UPLOADED_BY'    => 'APPROVAL'
                    ]
                );


                // Recalculate indents
                $this->recalculateIndentAllotments(
                    $oldPlanning['PP_ID'],
                    $newPlanning
                );

                // Mark approval
                $this->calendarApprovalModel->update(
                    $newPlanning['PP_ID'],
                    [
                        'APPROVAL_STATUS' => 'A',
                        'ACTION_DATE'     => date('Y-m-d H:i:s'),
                        'ACTION_BY'       => 'APPROVAL'
                    ]
                );
            }

            // CASE 2 : NEW PLANNING (PLANNING_CAL_ID = 0)
            foreach ($newRowIds as $approvalId) {

                $pending = $this->calendarApprovalModel
                    ->where('PP_ID', $approvalId)
                    ->where('APPROVAL_STATUS', 'P')
                    ->first();

                if (!$pending) {
                    continue;
                }

                // $pending['NKC_QTY_MT'] = $pending['QTY_MT'] - ($pending['KC1_QTY_MT'] + $pending['KC2_QTY_MT']);

                // $pending['NKC_QTY_MT'] =
                //     (float) ($pending['QTY_MT'] ?? 0)
                //     - (
                //         (float) ($pending['KC1_QTY_MT'] ?? 0)
                //         + (float) ($pending['KC2_QTY_MT'] ?? 0)
                //     );

                // echo '<pre>';
                // print_r($pending);
                // echo '</pre>';
                // exit;

                // Insert into production planning
                $newPPId = $this->model->insert([
                    'VERSION' => 1,
                    'MACHINE' => $pending['MACHINE'],
                    'SAP_MR_FG_CODE' => $pending['SAP_MR_FG_CODE'],
                    'FROM_DATE_TIME' => $pending['FROM_DATE_TIME'],
                    'TO_DATE_TIME' => $pending['TO_DATE_TIME'],
                    'QTY_MT' => $pending['QTY_MT'],
                    'BALANCE_QTY' => $pending['QTY_MT'],
                    'KC1_QTY_MT' => $pending['KC1_QTY_MT'],
                    'KC2_QTY_MT' => $pending['KC2_QTY_MT'],
                    'NKC_QTY_MT' => $pending['NKC_QTY_MT'],
                    'KC1_BALANCE_QTY_MT' => $pending['KC1_QTY_MT'],
                    'KC2_BALANCE_QTY_MT' => $pending['KC2_QTY_MT'],
                    'NKC_BALANCE_QTY_MT' => $pending['NKC_BALANCE_QTY_MT'],
                    'CALENDAR_TYPE' => 'M',
                    'UPLOADED_BY' => 'APPROVAL',
                    'UPLOADED_DATE' => date('Y-m-d H:i:s'),
                ], true);

                // Update calendar approval safely
                $this->calendarApprovalModel->update(
                    (int) $approvalId,
                    [
                        'PLANNING_CAL_ID' => $newPPId,
                        'APPROVAL_STATUS' => 'A',
                        'ACTION_DATE'     => date('Y-m-d H:i:s'),
                        'ACTION_BY'       => 'APPROVAL'
                    ]
                );
            }

            $db->transCommit();

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Reorder planning approved successfully'
            ]);
        } catch (\Throwable $e) {

            $db->transRollback();

            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateProductionPlanningOrder()
    {
        $order = $this->request->getPost('order');



        if (empty($order) || !is_array($order)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid order data'
            ]);
        }

        $db = \Config\Database::connect();
        $model = $this->model;

        $db->transStart();

        $existingIds = [];
        foreach ($order as $row) {
            if (!empty($row['id']) && $row['id'] !== 'undefined') {
                $existingIds[] = (int) $row['id'];
            }
        }

        $existingRows = [];
        if (!empty($existingIds)) {
            $dbRows = $model
                ->whereIn('PP_ID', $existingIds)
                ->findAll();

            foreach ($dbRows as $r) {
                $existingRows[$r['PP_ID']] = $r;
            }
        }

        $orderedRows = [];


        foreach ($order as $row) {
            if (!empty($row['id']) && $row['id'] !== 'undefined') {
                // Existing DB row
                if (isset($existingRows[$row['id']])) {
                    $orderedRows[] = $existingRows[$row['id']];
                }
            } else {

                $row['NKC_QTY_MT'] =
                    (float) ($row['QTY_MT'] ?? 0)
                    - (
                        (float) ($row['KC1_QTY_MT'] ?? 0)
                        + (float) ($row['KC2_QTY_MT'] ?? 0)
                    );

                // New UI-only row
                $orderedRows[] = [
                    'PP_ID' => null,
                    'VERSION' => 0,
                    'MACHINE' => $row['machine'],
                    'SAP_MR_FG_CODE' => $row['SAP_MR_FG_CODE'],
                    'QTY_MT' => $row['QTY_MT'],
                    'BALANCE_QTY' => $row['QTY_MT'],
                    'KC1_QTY_MT' => $row['KC1_QTY_MT'],
                    'KC2_QTY_MT' => $row['KC2_QTY_MT'],
                    'NKC_QTY_MT' => $row['NKC_QTY_MT'],
                    'KC1_BALANCE_QTY_MT' => $row['KC1_QTY_MT'],
                    'KC2_BALANCE_QTY_MT' => $row['KC2_QTY_MT'],
                    'NKC_BALANCE_QTY_MT' => $row['NKC_QTY_MT'],
                ];
            }
        }

        if (empty($orderedRows)) {
            return $this->response->setJSON(['status' => false]);
        }

        $firstDate = null;
        foreach ($orderedRows as $r) {
            if (!empty($r['FROM_DATE_TIME'])) {
                $firstDate = $r['FROM_DATE_TIME'];
                break;
            }
        }

        if (!$firstDate) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'FROM_DATE_TIME not found'
            ]);
        }

        $startDateTime = new \DateTime($firstDate);
        $prevGrade = null;
        $prevGsm = null;

        foreach ($orderedRows as $index => $row) {

            // Machine master
            $machine = $db->table('pp_machine_master')
                ->where('PP_ID', $row['MACHINE'])
                ->get()
                ->getRowArray();

            if (!$machine)
                continue;

            // Material master
            $material = $db->table('pp_mr_material_master')
                ->where('MR_MATERIAL_CODE', $row['SAP_MR_FG_CODE'])
                ->where('SAP_PLANT', $machine['SAP_PLANT'])
                ->get()
                ->getRowArray();

            if (!$material)
                continue;

            $additionalMinutes = 0;

            if ($index > 0) {
                if ($material['GRADE'] !== $prevGrade) {
                    $additionalMinutes += $machine['GRADE_CHANGE_TIME_MIN'];
                } elseif ($material['GSM'] !== $prevGsm) {
                    $additionalMinutes += $machine['GSM_CHANGE_TIME_MIN'];
                }
            }

            $fromDateTime = clone $startDateTime;
            if ($additionalMinutes > 0) {
                $fromDateTime->modify("+{$additionalMinutes} minutes");
            }

            $plannedQtyKg = $row['QTY_MT'];
            $outputKgHr = $material['MACHINE_OUTPUT_KG_HR'];

            $productionHours = $outputKgHr > 0
                ? ($plannedQtyKg / $outputKgHr)
                : 0;

            $hours = floor($productionHours);
            $minutes = round(($productionHours - $hours) * 60);

            $toDateTime = clone $fromDateTime;
            $toDateTime->modify("+{$hours} hours +{$minutes} minutes");

            $db->table('pp_calendar_approval')->insert([
                'PLANNING_CAL_ID' => $row['PP_ID'] ?? 0, // null for new row
                'VERSION' => $row['VERSION'] + 1,
                'MACHINE' => $row['MACHINE'],
                'SAP_MR_FG_CODE' => $row['SAP_MR_FG_CODE'],
                'FROM_DATE_TIME' => $fromDateTime->format('Y-m-d H:i:s'),
                'TO_DATE_TIME' => $toDateTime->format('Y-m-d H:i:s'),
                'QTY_MT' => $row['QTY_MT'],
                'UTILISED_QTY' => $row['UTILISED_QTY'] ?? 0,
                'BALANCE_QTY' => $row['BALANCE_QTY'],
                'KC1_QTY_MT' => $row['KC1_QTY_MT'],
                'KC2_QTY_MT' => $row['KC2_QTY_MT'],
                'NKC_QTY_MT' => $row['NKC_QTY_MT'],
                'KC1_UTILISED_QTY_MT' => $row['KC1_UTILISED_QTY_MT'] ?? 0,
                'KC2_UTILISED_QTY_MT' => $row['KC2_UTILISED_QTY_MT'] ?? 0,
                'NKC_UTILISED_QTY_MT' => $row['NKC_UTILISED_QTY_MT'] ?? 0,
                'KC1_BALANCE_QTY_MT' => $row['KC1_BALANCE_QTY_MT'],
                'KC2_BALANCE_QTY_MT' => $row['KC2_BALANCE_QTY_MT'],
                'NKC_BALANCE_QTY_MT' => $row['NKC_BALANCE_QTY_MT'],
                'APPROVAL_STATUS' => 'P',
                'UPLOADED_DATE' => date('Y-m-d H:i:s'),
            ]);

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

        $existingIds = $approvalModel
            ->select('PLANNING_CAL_ID')
            ->findColumn('PLANNING_CAL_ID');

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

            $machineId = $this->request->getPost('machine');
            $machineMaterialCode = $this->request->getPost('sap_mother_roll_code');
            $plannedQty = (float) $this->request->getPost('qty_mt');


            if (!$machineId || !$machineMaterialCode || $plannedQty <= 0) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Invalid input data'
                ]);
            }

            $machine = $db->table('pp_machine_master')
                ->where('PP_ID', $machineId)
                ->get()
                ->getRowArray();


            if (!$machine) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Machine not found: {$machineId}"
                ]);
            }

            $machinePPId = $machine['PP_ID'];
            $gsmChangeTime = $machine['GSM_CHANGE_TIME_MIN'];
            $gradeChangeTime = $machine['GRADE_CHANGE_TIME_MIN'];
            $sapplant = $machine['SAP_PLANT'];

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

            $plannedQtyKg = $plannedQty;
            $productionHr = ($machineOutputKgHr > 0)
                ? ($plannedQtyKg / $machineOutputKgHr)
                : 0;

            $hours = floor($productionHr);
            $minutes = round(($productionHr - $hours) * 60);

            $toDateTime = clone $fromDateTime;
            $toDateTime->modify("+{$hours} hours +{$minutes} minutes");

            $quota = $db->table('pp_customer_quota_master')
                ->where('GRADE', $grade)
                ->orderBy('CUSTOMER_TYPE', 'ASC')
                ->get()
                ->getResultArray();

            // if ($quota) {
            //     $kc1Qty = $plannedQty * $quota[0]['QUOTA_PERCENTAGE'] / 100;
            //     $kc2Qty = $plannedQty * $quota[1]['QUOTA_PERCENTAGE'] / 100;
            // } else {
            //     $kc1Qty = 0;
            //     $kc2Qty = 0;
            // }

            $kc1Qty = 0;
            $kc2Qty = 0;

            if (is_array($quota)) {
                if (!empty($quota[0]['QUOTA_PERCENTAGE'])) {
                    $kc1Qty = $plannedQty * $quota[0]['QUOTA_PERCENTAGE'] / 100;
                }

                if (!empty($quota[1]['QUOTA_PERCENTAGE'])) {
                    $kc2Qty = $plannedQty * $quota[1]['QUOTA_PERCENTAGE'] / 100;
                }
            }

            $nkcQty = $plannedQty - ($kc1Qty + $kc2Qty);

            $data = [
                'VERSION' => 1,
                'MACHINE' => $machinePPId,
                'SAP_MR_FG_CODE' => $machineMaterialCode,
                'FROM_DATE_TIME' => $fromDateTime->format('Y-m-d H:i:s'),
                'TO_DATE_TIME' => $toDateTime->format('Y-m-d H:i:s'),
                'QTY_MT' => $plannedQty,
                'BALANCE_QTY' => $plannedQty,
                'KC1_QTY_MT' => $kc1Qty,
                'KC2_QTY_MT' => $kc2Qty,
                'NKC_QTY_MT' => $nkcQty,
                'KC1_BALANCE_QTY_MT' => $kc1Qty,
                'KC2_BALANCE_QTY_MT' => $kc2Qty,
                'NKC_BALANCE_QTY_MT' => $nkcQty,
                'APPROVAL_STATUS' => 'P',
                'UPLOADED_BY' => '',
                'UPLOADED_DATE' => date('Y-m-d H:i:s'),
            ];

            return $this->response->setJSON([
                'status' => true,
                'data' => $data,
                'message' => 'Production planning inserted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    private function recalculateIndentAllotments(int $planningCalId, array $newPlanning)
    {
        // 1 Fetch all allotments linked to this calendar
        $allotments = $this->indentAllotment
            ->where('PLANNING_CAL_ID', $planningCalId)
            ->findAll();

        if (empty($allotments)) {
            return;
        }

        foreach ($allotments as $allotment) {

            // 2 Base dates from approved planning
            $fromDate = $newPlanning['FROM_DATE_TIME'];
            $toDate = $newPlanning['TO_DATE_TIME'];

            // 3 Fetch material packaging time
            $material = $this->materialModel
                ->select('PACKAGING_TIME')
                ->where('FINISH_MATERIAL_CODE', $allotment['FINISH_MATERIAL_CODE'])
                ->first();

            $packagingDays = (int) ($material['PACKAGING_TIME'] ?? 0);

            // 4 Calculate FINISHING_DATE
            $finishingDate = new \DateTime($toDate);
            if ($packagingDays > 0) {
                $finishingDate->add(new \DateInterval("P{$packagingDays}D"));
            }

            // 5 Fetch transit time
            $transit = $this->transitMaster
                ->select('TRANSIT_TIME')
                ->where('FROM_PINCODE', $newPlanning['MACHINE_PINCODE'] ?? null)
                ->where('TO_PINCODE', $allotment['CUSTOMER_PIN_CODE'] ?? null)
                ->first();

            $transitDays = (int) ($transit['TRANSIT_TIME'] ?? 0);

            // 6 Calculate DOOR_STEP_DEL_DATE
            $doorStepDate = clone $finishingDate;
            if ($transitDays > 0) {
                $doorStepDate->add(new \DateInterval("P{$transitDays}D"));
            }

            // 7 Update indent allotment (NO quantity touch)
            $this->indentAllotment->update($allotment['PP_ID'], [
                'FROM_DATE' => $fromDate,
                'TO_DATE' => $toDate,
                'FINISHING_DATE' => $finishingDate->format('Y-m-d H:i:s'),
                'DOOR_STEP_DEL_DATE' => $doorStepDate->format('Y-m-d H:i:s'),
                'MODIFICATION_FLAG' => 'X'
            ]);
        }
    }
}
