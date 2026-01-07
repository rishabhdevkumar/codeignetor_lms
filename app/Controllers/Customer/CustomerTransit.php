<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\Crud_Model;
use App\Models\MasterModels\TransitMaster;

class CustomerTransit extends BaseController
{
	protected $session;
	protected $crudModel;
	protected $customerTransitModel;
	protected $helpers = ['url', 'form', 'security'];

	public function __construct()
	{
		// Load services
		$this->session        = session();
		$this->crudModel      = new Crud_Model();
		$this->customerTransitModel   = new TransitMaster();

		date_default_timezone_set('Asia/Calcutta');
	}

	public function index()
{
    $result['title'] = "Customer Transit";

    $where = [];
    $result['customer'] = $this->customerTransitModel->all_customerstransit($where);

    echo view('header', $result);
    echo view('customertransit/customertransit_view', $result);
    echo view('footer');
}


	public function add()
	{

		// $validation = \Config\Services::validation();

		$result["title"] = "Add Customer Transit";

		echo view('header', $result);
		echo view('customertransit/add_customertransit_view', $result);
		echo view('footer');
	}

	public function insertData()
	{

		$arr = [
			'FROM_COUNTRY'         => $this->request->getPost('from_country'),
			'FROM_PINCODE'          => $this->request->getPost('from_pincode'),
			'TO_COUNTRY'               => $this->request->getPost('to_country'),
			'TO_PINCODE'          => $this->request->getPost('to_pincode'),
			'DISTANCE'                 => $this->request->getPost('distance'),
			'TRANSIT_TIME'                 => $this->request->getPost('transit_time'),
		];

		// $arr2 = array('CUSTOMER_CODE' => $this->request->getPost('customer_code'));
		// $result["customer"] = $this->customerTransitModel->all_customerstransit($arr2);

		// if (!$result['customer']) {
			$insert = $this->crudModel->saveData('pp_customer_master', $arr);
		// } else {
		// 	$result['error'] = "Customer Already Exist";
		// 	return view('header', $result)
		// 		. view('customertransit/add_customertransit_view', $result)
		// 		. view('footer');
		// }

		if ($insert) {
			return redirect()->back()->with('success', 'Customer Transit Created');
		}
	}

	public function edit($id)
	{

		$arr = array('PP_ID' => $id);
		$dataList = $this->customerTransitModel->all_customerstransit($arr);

		$result["customer"] = $dataList[0];

		if (!$result['customer']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("customer not found");
		}

		$result["title"] = "Edit Customer Transit";


		echo view('header', $result);
		echo view('customertransit/edit_customertransit_view', $result);
		echo view('footer');
	}

	public function updateData($id)
	{

		$arr = [
			'FROM_COUNTRY'         => $this->request->getPost('from_country'),
			'FROM_PINCODE'          => $this->request->getPost('from_pincode'),
			'TO_COUNTRY'               => $this->request->getPost('to_country'),
			'TO_PINCODE'          => $this->request->getPost('to_pincode'),
			'DISTANCE'                 => $this->request->getPost('distance'),
			'TRANSIT_TIME'                 => $this->request->getPost('transit_time'),
		];

		$condition = array("PP_ID" => $this->request->getPost('transit_id'));
		$update = $this->crudModel->updateData('pp_customer_master', $arr, $condition);

		if ($update) {
			// $this->session->set_flashdata("message","<div class='alert alert-success'>Customer Updated</div>");
			return redirect()->to('/CustomerTransit')->with('success', 'Customer Updated');
		}
	}

	public function view($id)
	{
		$arr = array('PP_ID' => $id);
		$dataList = $this->customerTransitModel->all_customerstransit($arr);

		$result["customer"] = $dataList[0];


		if (!$result['customer']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Customer not found");
		}

		$result["title"] = "View Customer Transit";


		echo view('header', $result);
		echo view('customertransit/view_customertransit_view', $result);
		echo view('footer');
	}

	public function del($id)
	{
		$arr = array('PP_ID' => $id);
		$result['customer'] = $this->customerTransitModel->all_customerstransit($arr);

		if (!$result['customer']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Customer not found");
		}


		$condition = array("PP_ID" => $id);
		$delete = $this->crudModel->del("pp_customer_master", $condition);

		if ($delete) {

			// $this->session->set_flashdata("message","<div class='alert alert-success'>Customer Deleted</div>");
			return redirect()->to('/CustomerTransit');
		}
	}
}
