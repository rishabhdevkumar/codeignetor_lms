<?php

namespace App\Controllers\Machine;

use App\Models\Crud_Model;
use App\Models\Machine\MachineModel;
use CodeIgniter\Controller;

class Machine extends Controller
{
	protected $session;
	protected $crudModel;
	protected $machineModel;
	protected $helpers = ['url', 'form', 'security'];

	public function __construct()
	{
		// Load services
		$this->session        = session();
		$this->crudModel      = new Crud_Model();
		$this->machineModel   = new MachineModel();

		date_default_timezone_set('Asia/Calcutta');
	}

	public function index()
	{


		$result = [];

		// Page title
		$result['title'] = "Machine";

		// machine list
		$where = [];
		$result['machine'] = $this->machineModel->all_machine($where);


		echo view('header', $result);
		echo view('machine/machine_view', $result);
		echo view('footer');
	}

	public function add()
	{

		$result["title"] = "Add Machine";

		echo view('header', $result);
		echo view('machine/add_machine_view', $result);
		echo view('footer');
	}

	public function insertData()
	{

		$arr = [
			'MACHINE_TPM_ID'        => $this->request->getPost('machine_code'),
			'DESCRIPTION'           => $this->request->getPost('description'),
			'TYPE'                  => $this->request->getPost('type'),
			'PIN_CODE'              => $this->request->getPost('pincode'),
			'SAP_PLANT'             => $this->request->getPost('sap_plant'),
			'SAP_VENDOR_CODE'       => $this->request->getPost('vendor_code'),
			'CAPACITY_PER_DAY_MT'   => $this->request->getPost('capacity_per_day'),
			'FINISH_LOSS_PERCENT'   => $this->request->getPost('finish_loss'),
			'GRADE_CHANGE_TIME_MIN' => $this->request->getPost('grade_change_time'),
			'GSM_CHANGE_TIME_MIN'   => $this->request->getPost('gsm_change_time')
		];

		$arr2 = array('MACHINE_TPM_ID' => $this->request->getPost('machine_code'));
		$result["machine"] = $this->machineModel->all_machine($arr2);

		if (!$result['machine']) {
			$insert = $this->crudModel->saveData('pp_machine_master', $arr);
		} else {
			$result['error'] = "Machine Already Exist";
			return view('header', $result)
				. view('machine/add_machine_view', $result)
				. view('footer');
		}

		if ($insert) {
			return redirect()->to('/Machine')->with('success', 'Machine Created');
		}
	}

	public function edit($id)
	{

		$arr = array('PP_ID' => $id);
		$dataList = $this->machineModel->all_machine($arr);

		$result["machine"] = $dataList[0];

		if (!$result['machine']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("machine not found");
		}

		$result["title"] = "Edit Machine";


		echo view('header', $result);
		echo view('machine/edit_machine_view', $result);
		echo view('footer');
	}

	public function updateData($id)
	{

		$arr = [
			'MACHINE_TPM_ID'        => $this->request->getPost('machine_code'),
			'DESCRIPTION'           => $this->request->getPost('description'),
			'TYPE'                  => $this->request->getPost('type'),
			'PIN_CODE'              => $this->request->getPost('pin_code'),
			'SAP_PLANT'             => $this->request->getPost('sap_plant'),
			'SAP_VENDOR_CODE'       => $this->request->getPost('vendor_code'),
			'CAPACITY_PER_DAY_MT'   => $this->request->getPost('capacity_per_day'),
			'FINISH_LOSS_PERCENT'   => $this->request->getPost('finish_loss'),
			'GRADE_CHANGE_TIME_MIN' => $this->request->getPost('grade_change_time'),
			'GSM_CHANGE_TIME_MIN'   => $this->request->getPost('gsm_change_time')
		];

		$condition = array("PP_ID" => $this->request->getPost('machine_id'));
		$update = $this->crudModel->updateData('pp_machine_master', $arr, $condition);

		if ($update) {
			return redirect()->to('/Machine')->with('success', 'Machine Updated');
		}
	}


	public function view($id)
	{
		$arr = array('PP_ID' => $id);
		$dataList = $this->machineModel->all_machine($arr);

		$result["machine"] = $dataList[0];

		if (!$result['machine']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Machine not found");
		}

		$result["title"] = "View Machine";


		echo view('header', $result);
		echo view('machine/view_machine_view', $result);
		echo view('footer');
	}

	public function del($id)
	{
		$arr = array('PP_ID' => $id);
		$result['machine'] = $this->machineModel->all_machine($arr);

		if (!$result['machine']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Machine not found");
		}


		$condition = array("PP_ID" => $id);
		$delete = $this->crudModel->del("pp_machine_master", $condition);

		if ($delete) {
			return redirect()->to('/Machine');
		}
	}
}
