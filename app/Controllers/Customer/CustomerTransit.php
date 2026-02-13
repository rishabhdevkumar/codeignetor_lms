<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\Crud_Model;
use App\Models\Customer\CustomerTransitModel;
use \App\Models\Customer\CountryModel;

class CustomerTransit extends BaseController
{
	protected $session;
	protected $crudModel;
	protected $customerTransitModel;
	protected $countryModel;
	protected $helpers = ['url', 'form', 'security'];

	public function __construct()
	{
		$this->session                = session();
		$this->crudModel              = new Crud_Model();
		$this->customerTransitModel   = new CustomerTransitModel();
		$this->countryModel           = new CountryModel();

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

		$result["title"] = "Add Customer Transit";

		$result['countries'] = $this->countryModel->getActiveCountries();

		echo view('header', $result);
		echo view('customertransit/add_customertransit_view', $result);
		echo view('footer');
	}

	public function insertData()
	{

		$arr = [
			'FROM_COUNTRY'         => trim($this->request->getPost('from_country')),
			'FROM_PINCODE'         => trim($this->request->getPost('from_pincode')),
			'TO_COUNTRY'           => trim($this->request->getPost('to_country')),
			'TO_PINCODE'           => trim($this->request->getPost('to_pincode')),
			'DISTANCE'             => $this->request->getPost('distance'),
			'TRANSIT_TIME'         => $this->request->getPost('transit_time'),
		];

		$insert = $this->crudModel->saveData('pp_transit_master', $arr);

		if ($insert) {
			return redirect()->back()->with('success', 'Customer Transit Created');
		}
	}

	public function edit($id)
	{

		$arr = array('t.PP_ID' => $id);
		$dataList = $this->customerTransitModel->all_customerstransit($arr);

		$result["customer"] = $dataList[0];
		
		$result['countries'] = $this->countryModel->getActiveCountries();

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
			'FROM_COUNTRY'         => trim($this->request->getPost('from_country')),
			'FROM_PINCODE'         => trim($this->request->getPost('from_pincode')),
			'TO_COUNTRY'           => trim($this->request->getPost('to_country')),
			'TO_PINCODE'           => trim($this->request->getPost('to_pincode')),
			'DISTANCE'             => $this->request->getPost('distance'),
			'TRANSIT_TIME'         => $this->request->getPost('transit_time'),
		];

		$condition = array("PP_ID" => $this->request->getPost('transit_id'));
		$update = $this->crudModel->updateData('pp_transit_master', $arr, $condition);

		if ($update) {
			// $this->session->set_flashdata("message","<div class='alert alert-success'>Customer Updated</div>");
			return redirect()->to('/CustomerTransit')->with('success', 'Customer Updated');
		}
	}

	public function view($id)
	{
		$arr = array('t.PP_ID' => $id);
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
		$delete = $this->crudModel->del("pp_transit_master", $condition);

		if ($delete) {

			// $this->session->set_flashdata("message","<div class='alert alert-success'>Customer Deleted</div>");
			return redirect()->to('/CustomerTransit');
		}
	}
}
