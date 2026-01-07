<?php

namespace App\Controllers\ProductionPlanning;

use App\Controllers\BaseController;
use App\Models\ProductionPlanning\PlanningProductionModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PlanningProductionController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new PlanningProductionModel();
    }

    /* -------------------------------------------------------------
        LIST ALL RECORDS
    ------------------------------------------------------------- */
    public function index()
    {

        $data['records'] = $this->model->select('pp_production_planning_master.*, pp_machine_master.MACHINE_TPM_ID')
            ->join('pp_machine_master', 'pp_machine_master.PP_ID = pp_production_planning_master.MACHINE')
            // ->join('pp_mr_material_master', 'pp_mr_material_master.PP_ID = pp_production_planning_master.SAP_MR_FG_CODE')
            ->findAll();

        $data['title'] = "Production Planning";

        echo view('header', $data);
        return view('ProductionPlanning/index', $data);
        echo view('footer');
    }

    public function calendarView()
    {

        $data['records'] = $this->model->select('pp_production_planning_master.*, pp_machine_master.MACHINE_TPM_ID, COALESCE(pp_finish_material_master.GRADE, pp_mr_material_master.GRADE) AS GRADE,
                        COALESCE(pp_finish_material_master.GSM,   pp_mr_material_master.GSM)   AS GSM ')
            ->join('pp_machine_master', 'pp_machine_master.PP_ID = pp_production_planning_master.MACHINE')
            ->join('pp_mr_material_master', 'pp_mr_material_master.MR_MATERIAL_CODE = pp_production_planning_master.SAP_MR_FG_CODE', 'left')
            ->join('pp_finish_material_master', 'pp_finish_material_master.FINISH_MATERIAL_CODE = pp_production_planning_master.SAP_MR_FG_CODE', 'left')
            ->findAll();

        $data['title'] = "Production Planning";

        echo view('header', $data);
        return view('ProductionPlanning/calendarView', $data);
        echo view('footer');
    }

    /* -------------------------------------------------------------
        CREATE FORM
    ------------------------------------------------------------- */
    public function create()
    {
        $data['title'] = "Production Planning";

        echo view('header', $data);
        return view('ProductionPlanning/create');
        echo view('footer');
    }

    /* -------------------------------------------------------------
        SAVE NEW RECORD
    ------------------------------------------------------------- */
    public function store()
    {
        $this->model->save([
            'VERSION' => $this->request->getPost('VERSION'),
            'MACHINE' => $this->request->getPost('MACHINE'),
            'SAP_MR_FG_CODE' => $this->request->getPost('SAP_MR_FG_CODE'),
            'QTY_MT' => $this->request->getPost('QTY_MT'),
            'FROM_DATE_TIME' => $this->request->getPost('FROM_DATE_TIME'),
            'TO_DATE_TIME' => $this->request->getPost('TO_DATE_TIME'),
            'UTILISED_QTY' => $this->request->getPost('UTILISED_QTY'),
            'BALANCE_QTY' => $this->request->getPost('BALANCE_QTY'),
            'KC1_QTY_MT' => $this->request->getPost('KC1_QTY_MT'),
            'KC2_QTY_MT' => $this->request->getPost('KC2_QTY_MT'),
            'KC1_UTILISED_QTY_MT' => $this->request->getPost('KC1_UTILISED_QTY_MT'),
            'KC2_UTILISED_QTY_MT' => $this->request->getPost('KC2_UTILISED_QTY_MT'),
            'KC1_BALANCE_QTY_MT' => $this->request->getPost('KC1_BALANCE_QTY_MT'),
            'KC2_BALANCE_QTY_MT' => $this->request->getPost('KC2_BALANCE_QTY_MT'),
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

        $data['title'] = "Production Planning";

        echo view('header', $data);
        return view('ProductionPlanning/edit', $data);
        echo view('footer');
    }

    /* -------------------------------------------------------------
        UPDATE RECORD
    ------------------------------------------------------------- */
    public function update($id)
    {
        $this->model->update($id, [
            'VERSION' => $this->request->getPost('VERSION'),
            'MACHINE' => $this->request->getPost('MACHINE'),
            'SAP_MR_FG_CODE' => $this->request->getPost('SAP_MR_FG_CODE'),
            'QTY_MT' => $this->request->getPost('QTY_MT'),
            'FROM_DATE_TIME' => $this->request->getPost('FROM_DATE_TIME'),
            'TO_DATE_TIME' => $this->request->getPost('TO_DATE_TIME'),
            'UTILISED_QTY' => $this->request->getPost('UTILISED_QTY'),
            'BALANCE_QTY' => $this->request->getPost('BALANCE_QTY'),
            'KC1_QTY_MT' => $this->request->getPost('KC1_QTY_MT'),
            'KC2_QTY_MT' => $this->request->getPost('KC2_QTY_MT'),
            'KC1_UTILISED_QTY_MT' => $this->request->getPost('KC1_UTILISED_QTY_MT'),
            'KC2_UTILISED_QTY_MT' => $this->request->getPost('KC2_UTILISED_QTY_MT'),
            'KC1_BALANCE_QTY_MT' => $this->request->getPost('KC1_BALANCE_QTY_MT'),
            'KC2_BALANCE_QTY_MT' => $this->request->getPost('KC2_BALANCE_QTY_MT'),
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

            $rows = array_filter($rows, function ($row) {
                return count(array_filter($row, fn($cell) => trim((string)$cell) !== '')) > 0;
            });

            $rows = array_values($rows);


            // Sort by first column (machine TPM ID)
            // usort($rows, function ($a, $b) {
            //     return $a[0] <=> $b[0];
            // });

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

                // echo "<pre>";
                // print_r($rows);
                // exit;

                // Skip header or empty rows
                if ($i == 0 || empty($row[0])) {
                    continue;
                }



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
                $qouta = $db->table('pp_customer_quota_master')
                    ->where('GRADE', $grade)
                    ->orderBy('CUSTOMER_TYPE', 'ASC')
                    ->get()
                    ->getResultArray();

                if ($qouta) {
                    $kc1Quota = $plannedQty * $qouta[0]['QUOTA_PERCENTAGE'] / 100;
                    $kc2Quota = $plannedQty * $qouta[1]['QUOTA_PERCENTAGE'] / 100;
                } else {
                    $kc1Quota = 0;
                    $kc2Quota = 0;
                }

                // Insert Final Row
                $data = [
                    'VERSION' => 1,
                    'MACHINE' => $machinePPId,
                    'SAP_MR_FG_CODE' => $machineMaterialCode,
                    'QTY_MT' => $plannedQty,
                    'BALANCE_QTY' => $plannedQty,
                    'KC1_QTY_MT' => $kc1Quota,
                    'KC2_QTY_MT' => $kc2Quota,
                    'KC1_BALANCE_QTY_MT' => $kc1Quota,
                    'KC2_BALANCE_QTY_MT' => $kc2Quota,
                    'FROM_DATE_TIME' => $fromDateTimeStr,
                    'TO_DATE_TIME' => $toDateTimeStr,
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
}
