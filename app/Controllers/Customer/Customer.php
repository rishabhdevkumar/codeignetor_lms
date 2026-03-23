<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\Crud_Model;
use App\Models\Customer\CustomerModel;
use CodeIgniter\Controller;

class Customer extends BaseController
{
	protected $session;
	protected $crudModel;
	protected $customerModel;
	protected $helpers = ['url', 'form', 'security'];

	public function __construct()
	{
		// Load services
		$this->session        = session();
		$this->crudModel      = new Crud_Model();
		$this->customerModel   = new CustomerModel();

		date_default_timezone_set('Asia/Calcutta');
	}

	public function index()
	{
		//  if (!$this->session->get('user_id')) {
		// return redirect()->to('/auth/logout');
		// // }

		$result = [];

		// Page title
		$result['title'] = "Customer";

		// $data = customerModel->getCustomers(['m.TYPE' => 'EXPORT']);
		// customer list
		$where = [];   // previously $data
		$result['customer'] = $this->customerModel->getCustomers($where);


		echo view('header', $result);
		echo view('customer/customer_view', $result);
		echo view('footer');
	}

	public function add()
	{

		// $validation = \Config\Services::validation();

		$result["title"] = "Add Customer";

		echo view('header', $result);
		echo view('customer/add_customer_view', $result);
		echo view('footer');
	}

	public function insertData()
	{

		$arr = [
			'cust_no'               => trim($this->request->getPost('customer_code')),
			'customer_type'         => $this->request->getPost('customer_type'),
			'country'               => $this->request->getPost('country'),
			'postal_code'           => trim($this->request->getPost('pincode')),
			'state'                 => $this->request->getPost('state'),
		];

		$arr2 = array('cust_no' => trim($this->request->getPost('cust_no')));
		$result["customer"] = $this->customerModel->all_customer($arr2);

		if (!$result['customer']) {
			$insert = $this->crudModel->saveData('vtiger_bp_customer_master', $arr);
		} else {
			$result['error'] = "Customer Already Exist";
			return view('header', $result)
				. view('customer/add_customer_view', $result)
				. view('footer');
		}

		if ($insert) {
			return redirect()->back()->with('success', 'Customer Created');
		}
	}

	public function edit($id)
	{

		$arr = array('id' => $id);
		$dataList = $this->customerModel->all_customer($arr);

		$result["customer"] = $dataList[0];

		if (!$result['customer']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("customer not found");
		}

		$result["title"] = "Edit Customer";


		echo view('header', $result);
		echo view('customer/edit_customer_view', $result);
		echo view('footer');
	}

	public function updateData($id)
	{

		$arr = [
			'cust_no'               => trim($this->request->getPost('customer_code')),
			'customer_type'         => $this->request->getPost('customer_type'),
			'country'               => $this->request->getPost('country'),
			'postal_code'           => trim($this->request->getPost('pincode')),
			'state'                 => $this->request->getPost('state'),
		];

		$condition = array("cust_no" => $this->request->getPost('customer_code'));
		$update = $this->crudModel->updateData('vtiger_bp_customer_master', $arr, $condition);

		if ($update) {
			// $this->session->set_flashdata("message","<div class='alert alert-success'>Customer Updated</div>");
			return redirect()->to('/Customer')->with('success', 'Customer Updated');
		}
	}

	public function view($id)
	{
		$arr = array('id' => $id);
		$dataList = $this->customerModel->all_customer($arr);

		$result["customer"] = $dataList[0];


		if (!$result['customer']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Customer not found");
		}

		$result["title"] = "View Customer";


		echo view('header', $result);
		echo view('customer/view_customer_view', $result);
		echo view('footer');
	}

}
