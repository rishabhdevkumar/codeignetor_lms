<?php

namespace App\Controllers\Machine;

use App\Models\Crud_Model;
use App\Models\Machine\MachineAvailabilityModel;
use CodeIgniter\Controller;

class MachineAvailability extends Controller
{
    protected $session;
    protected $crudModel;
    protected $machineAvailabilityModel;
    protected $helpers = ['url', 'form', 'security'];

    public function __construct()
    {
        $this->session = session();
        $this->crudModel = new Crud_Model();
        $this->machineAvailabilityModel = new MachineAvailabilityModel();

        date_default_timezone_set('Asia/Calcutta');
    }

    public function index()
    {
        $result = [];

        $result['title'] = "Machine Availability";

        $where = [];
        $result['machineavailability'] = $this->machineAvailabilityModel->all_machine_availability($where);

        echo view('header', $result);
        echo view('machineavailability/machineavailability_view', $result);
        echo view('footer');
    }

    public function add()
    {
        $result['title'] = "Add Machine Availability";

        echo view('header', $result);
        echo view('machineavailability/add_machineavailability_view', $result);
        echo view('footer');
    }

    public function insertData()
    {
        $arr = [
            'MACHINE_TPM_ID'     => $this->request->getPost('machine_tpm_id'),
            'SAP_NOTIFICATION_NO'=> $this->request->getPost('sap_notification_no'),
            'TYPE'               => $this->request->getPost('type'),
            'FROM_DATE'          => $this->request->getPost('from_date'),
            'TO_DATE'            => $this->request->getPost('to_date')
        ];

        $arr2 = [
            'MACHINE_TPM_ID' => $this->request->getPost('machine_tpm_id'),
            'FROM_DATE'     => $this->request->getPost('from_date')
        ];

        $check = $this->machineAvailabilityModel->all_machine_availability($arr2);

        if (!$check) {
            $insert = $this->crudModel->saveData('pp_machine_availability', $arr);
        } else {
            $result['error'] = "Machine Availability Already Exists";
            return view('header', $result)
                . view('machineavailability/add_machineavailability_view', $result)
                . view('footer');
        }

        if ($insert) {
            return redirect()->to('/MachineAvailability')->with('success', 'Machine Availability Added');
        }
    }

    public function edit($id)
{
    $arr = array('PP_ID' => $id);
    $dataList = $this->machineAvailabilityModel->all_machine_availability($arr);

    if (!$dataList) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Machine Availability not found");
    }

    $result["machine"] = $dataList[0];
    $result["title"]   = "Edit Machine Availability";

    echo view('header', $result);
    echo view('machineavailability/edit_machineavailability_view', $result);
    echo view('footer');
}


    public function updateData()
    {
        $arr = [
            'MACHINE_TPM_ID'     => $this->request->getPost('machine_tpm_id'),
            'SAP_NOTIFICATION_NO'=> $this->request->getPost('sap_notification_no'),
            'TYPE'               => $this->request->getPost('type'),
            'FROM_DATE'          => $this->request->getPost('from_date'),
            'TO_DATE'            => $this->request->getPost('to_date')
        ];

        $condition = ['PP_ID' => $this->request->getPost('machineavailability_id')];

        $update = $this->crudModel->updateData('pp_machine_availability', $arr, $condition);

        if ($update) {
            return redirect()->to('/MachineAvailability')->with('success', 'Machine Availability Updated');
        }
    }

   public function view($id)
{
    $arr = ['PP_ID' => $id];
    $dataList = $this->machineAvailabilityModel->all_machine_availability($arr);

    if (!$dataList) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Machine Availability not found");
    }

    $result['machine'] = $dataList[0];  // pass a single record to view
    $result['title'] = "View Machine Availability";

    echo view('header', $result);
    echo view('machineavailability/view_machineavailability_view', $result);
    echo view('footer');
}


    public function delete($id)
    {
        $condition = ['PP_ID' => $id];
        $this->crudModel->del('pp_machine_availability', $condition);

        return redirect()->to('/MachineAvailability')->with('success', 'Record Deleted');
    }
}
