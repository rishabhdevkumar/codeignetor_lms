<?php

namespace App\Controllers\Material;

use App\Models\Crud_Model;
use App\Models\Material\MRMaterialModel;
use CodeIgniter\Controller;

class MRMaterial extends Controller
{
	protected $session;
	protected $crudModel;
	protected $materialModel;
	protected $helpers = ['url', 'form', 'security'];

	public function __construct()
	{
		// Load services
		$this->session        = session();
		$this->crudModel      = new Crud_Model();
		$this->materialModel  = new MRMaterialModel();

		date_default_timezone_set('Asia/Calcutta');
	}

	public function index()
	{
		//  if (!$this->session->get('user_id')) {
		// return redirect()->to('/auth/logout');
		// // }

		$result = [];

		// Page title
		$result['title'] = "Material";

		// Material list
		$where = [];   // previously $data
		$result['material'] = $this->materialModel->all_material($where);
		// echo "<pre>";print_R($result);die();

		// Render views in CI4
		echo view('header', $result);
		echo view('mrmaterial/mrmaterial_view', $result);
		echo view('footer');
	}

	public function add()
	{

		$result["title"] = "Add Material";

		echo view('header', $result);
		echo view('mrmaterial/add_mrmaterial_view', $result);
		echo view('footer');
	}

	public function insertData()
	{

		$arr = [
			'MR_MATERIAL_CODE'     => trim($this->request->getPost('material_code')),
			'SAP_PLANT'            => trim($this->request->getPost('sap_plant')),
			'GRADE'                => trim($this->request->getPost('grade')),
			'GSM'                  => $this->request->getPost('gsm'),
			'DESCRIPTION'          => $this->request->getPost('description'),
			'DELIVERY_PLANT_YN'    => $this->request->getPost('delivery_plant'),
			'MACHINE_OUTPUT_KG_HR' => $this->request->getPost('machine_output')
		];

		$arr2 = array(
			'MR_MATERIAL_CODE' =>  trim($this->request->getPost('material_code')),
			'SAP_PLANT'        =>  trim($this->request->getPost('sap_plant'))
		);
		$result["material"] = $this->materialModel->all_material($arr2);

		if (!$result['material']) {

			$insert = $this->crudModel->saveData('pp_mr_material_master', $arr);
		} else {

			$result['error'] = "Material Already Exist!";
			return view('header', $result)
				. view('mrmaterial/add_mrmaterial_view', $result)
				. view('footer');
		}

		if ($insert) {
			return redirect()->to('/MRMaterial')->with('success', 'MR Material Created');
		}
	}

	public function edit($id)
	{

		$arr = array('PP_ID' => $id);
		$dataList = $this->materialModel->all_material($arr);

		$result["material"] = $dataList[0];

		if (!$result['material']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Material not found");
		}

		$result["title"] = "Edit Material";

		echo view('header', $result);
		echo view('mrmaterial/edit_mrmaterial_view', $result);
		echo view('footer');
	}

	public function updateData()
	{

		$arr = [
			'MR_MATERIAL_CODE'     => trim($this->request->getPost('material_code')),
			'SAP_PLANT'            => trim($this->request->getPost('sap_plant')),
			'GRADE'                => trim($this->request->getPost('grade')),
			'GSM'                  => $this->request->getPost('gsm'),
			'DESCRIPTION'          => $this->request->getPost('description'),
			'DELIVERY_PLANT_YN'    => $this->request->getPost('delivery_plant'),
			'MACHINE_OUTPUT_KG_HR' => $this->request->getPost('machine_output')
		];

		$condition = array("PP_ID" =>  $this->request->getPost('material_id'));
		$update = $this->crudModel->updateData('pp_mr_material_master', $arr, $condition);

		if ($update) {
			return redirect()->to('/MRMaterial')->with('success', 'Material Updated');
			// redirect('Material');
		}
	}

	public function view($id)
	{
		$arr = array('PP_ID' => $id);
		$dataList = $this->materialModel->all_material($arr);

		$result["material"] = $dataList[0];

		if (!$result['material']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Material not found");
		}

		$result["title"] = "View Material";

		echo view('header', $result);
		echo view('mrmaterial/view_mrmaterial_view', $result);
		echo view('footer');
	}

	public function del($id)
	{
		$arr = array('PP_ID' => $id);
		$result['material'] = $this->materialModel->all_material($arr);

		if (!$result['material']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Material not found");
		}


		$condition = array("PP_ID" => $id);
		$delete = $this->crudModel->del("materials", $condition);

		if ($delete) {

			// $this->session->set_flashdata("message","<div class='alert alert-success'>Material Deleted</div>");
			redirect('MRMaterial');
		}
	}
}
