<?php

namespace App\Controllers;

use App\Models\Crud_Model;
use CodeIgniter\Controller;

class Dashboard extends BaseController
{
	protected $session;
	protected $crudModel;
	protected $helpers = ['url', 'form', 'security'];

	public function __construct()
	{
		// Load services
		$this->session        = session();
		$this->crudModel      = new Crud_Model();

		date_default_timezone_set('Asia/Calcutta');
	}


	public function index()
	{

		if ($this->session->get('erp_user_id')) {

			$arr = array("PP_ID" => 1);
			$result["settings"] = $this->crudModel->select("pp_settings", $arr, "PP_ID", "ASC");

			$user_id = $this->session->get('erp_user_id');

			$arr = array("PP_ID" => $user_id);
			$result["user_details"] = $this->crudModel->select("pp_users_master", $arr, "PP_ID", "ASC");

			$result["title"] = "Dashboard";

			echo view('header', $result);
			echo view('dashboard/dashboard_view', $result);
			echo view('footer');
		} else {
			return redirect()->to('Auth/login');
		}
	}
}
