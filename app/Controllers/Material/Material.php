<?php

namespace App\Controllers\Material;

use App\Models\Crud_Model;
use App\Models\Material\MaterialModel;
use CodeIgniter\Controller;


use App\Controllers\BaseController;


class Material extends BaseController
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
		$this->materialModel  = new MaterialModel();

		date_default_timezone_set('Asia/Calcutta');
	}

	public function index()
	{
		$result = [];

		// Page title
		$result['title'] = "Material";

		// Material list
		$where = [];   // previously $data
		$result['material'] = $this->materialModel->all_material($where);
		// echo "<pre>";print_R($result);die();

		// Render views in CI4
		echo view('header', $result);
		echo view('material/material_view', $result);
		echo view('footer');
	}

	public function add()
	{
		$result["title"] = "Add Material";

		echo view('header', $result);
		echo view('material/add_material_view', $result);
		echo view('footer');
	}

	public function insertData()
	{
			$arr = [
				'FINISH_MATERIAL_CODE'  => $this->request->getPost('material_code'),
				'SAP_PLANT'             => $this->request->getPost('sap_plant'),
				'GRADE'                 => $this->request->getPost('grade'),
				'GSM'                   => $this->request->getPost('gsm'),
				'UOM'                   => $this->request->getPost('uom'),
				'ITEM_TYPE'             => $this->request->getPost('item_type'),
				'WIDTH'                 => $this->request->getPost('width'),
				'LENGTH'    		    => $this->request->getPost('length'),
				'MR_MATERIAL_CODE'      => $this->request->getPost('mr_material_code'),
				'PACKAGING_TIME'        => $this->request->getPost('packaging_time'),
				'DESCRIPTION'           => $this->request->getPost('description'),
			];

			$arr2 = array(
				'FINISH_MATERIAL_CODE' => $this->request->getPost('material_code'),
				'SAP_PLANT'        => $this->request->getPost('sap_plant')
			);
			$result["material"] = $this->materialModel->all_material($arr2);

			if (!$result['material']) {
				$insert = $this->crudModel->saveData('pp_finish_material_master', $arr);
			} else {
				$result['error'] = "Material Already Exist!";
				return view('header', $result)
					. view('material/add_material_view', $result)
					. view('footer');
			}

			if ($insert) {
				return redirect()->to('/Material')->with('success', 'Material Created');
			}

	}

	public function edit($id)
	{

		$arr = array('ID' => $id);
		$dataList = $this->materialModel->all_material($arr);

		$result["material"] = $dataList[0];

		if (!$result['material']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Material not found");
		}

		$result["title"] = "Edit Material";

		echo view('header', $result);
		echo view('material/edit_material_view', $result);
		echo view('footer');
	}

	public function updateData($id)
	{

			$arr = [
				'FINISH_MATERIAL_CODE'  => $this->request->getPost('material_code'),
				'SAP_PLANT'             => $this->request->getPost('sap_plant'),
				'GRADE'                 => $this->request->getPost('grade'),
				'GSM'                   => $this->request->getPost('gsm'),
				'UOM'                   => $this->request->getPost('uom'),
				'ITEM_TYPE'             => $this->request->getPost('item_type'),
				'WIDTH'                 => $this->request->getPost('width'),
				'LENGTH'    		    => $this->request->getPost('length'),
				'MR_MATERIAL_CODE'      => $this->request->getPost('mr_material_code'),
				'PACKAGING_TIME'        => $this->request->getPost('packaging_time'),
				'DESCRIPTION'           => $this->request->getPost('description'),
			];

			$condition = array("ID" => $this->request->getPost('material_id'));
			$update = $this->crudModel->updateData('pp_finish_material_master', $arr, $condition);

			if ($update) {
				return redirect()->to('/Material')->with('success', 'Material Updated');
				// redirect('Material');
			}
	}

	public function view($id)
	{
		$arr = array('ID' => $id);
		$dataList = $this->materialModel->all_material($arr);

		$result["material"] = $dataList[0];

		if (!$result['material']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Material not found");
		}

		$result["title"] = "View Material";

		echo view('header', $result);
		echo view('material/view_material_view', $result);
		echo view('footer');
	}

	public function del($id)
	{
		$arr = array('ID' => $id);
		$result['material'] = $this->materialModel->all_material($arr);

		if (!$result['material']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Material not found");
		}


		$condition = array("PP_ID" => $id);
		$delete = $this->crudModel->del("materials", $condition);

		if ($delete) {

			// $this->session->set_flashdata("message","<div class='alert alert-success'>Material Deleted</div>");
			redirect('Material');
		}
	}
}
