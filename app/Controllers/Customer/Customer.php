<?php

namespace App\Controllers\Customer;

use App\Models\Crud_Model;
use App\Models\Customer\CustomerModel;
use CodeIgniter\Controller;

class Customer extends Controller
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


		if ($this->request->getMethod() === 'POST') {

			$arr = [
				'CUSTOMER_CODE'        => $this->request->getPost('customer_code'),
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

            $arr2 = array('CUSTOMER_CODE' => $this->request->getPost('customer_code'));
            $result["customer"] = $this->customerModel->all_customer($arr2);

            if (!$result['customer']) {
			$insert = $this->crudModel->saveData('pp_customer_master', $arr);
            }else{
               $result['error'] = "Customer Already Exist";
                 return view('header', $result)
                        . view('customer/add_customer_view', $result)
                        . view('footer');
            }

			if ($insert) {
                return redirect()->back()->with('success', 'Customer Created');
			}
		}


		echo view('header', $result);
		echo view('customer/add_customer_view', $result);
		echo view('footer');
	}

	public function edit($id)
	{

		$arr = array('PP_ID' => $id);
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
				'CUSTOMER_CODE'        => $this->request->getPost('customer_code'),
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

			$condition = array("PP_ID" => $this->request->getPost('customer_id'));
			$update = $this->crudModel->updateData('pp_customer_master', $arr, $condition);

			if ($update) {
				// $this->session->set_flashdata("message","<div class='alert alert-success'>Customer Updated</div>");
				return redirect()->to('/Customer')->with('success', 'Customer Updated');
			}
		}	

	public function view($id)
	{
		$arr = array('PP_ID' => $id);
		$dataList = $this->customerModel->all_customer($arr);

		$result["customer"] = $dataList[0];

		if (!$result['customer']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Customer not found");
		}

		$result["title"] = "View Customer";


		echo view('customer', $result);
		echo view('customer/view_customer_view', $result);
		echo view('footer');
	}

	public function del($id)
	{
		$arr = array('PP_ID' => $id);
		$result['customer'] = $this->customerModel->all_customer($arr);

		if (!$result['customer']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Customer not found");
		}


		$condition = array("PP_ID" => $id);
		$delete = $this->crudModel->del("pp_customer_master", $condition);

		if ($delete) {

			// $this->session->set_flashdata("message","<div class='alert alert-success'>Customer Deleted</div>");
			return redirect()->to('/Customer');
		}
	}
}
