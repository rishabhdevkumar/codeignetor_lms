<?php

namespace App\Controllers\User;

use App\Models\Crud_Model;
use App\Models\User_Model;
use Config\Database;
use App\Controllers\BaseController;

class User extends BaseController
{
	protected $session;
	protected $crudModel;
	protected $userModel;

	public function __construct()
	{

		// Session service
		$this->session = session();

		// Load helpers
		helper([
			'url',
			'form',
			'security',
			'access_helper',
			'encryption'
		]);

		// Load models
		$this->crudModel = new Crud_Model();
		$this->userModel = new User_Model();
	}

	public function index()
{
    $arr = array("PP_ID" => 1);
    $settings = $this->crudModel->select("pp_settings", $arr, "PP_ID", "ASC");

    $result["settings"] = $settings[0] ?? []; // ✅ Fix applied

    $user_id = $this->session->get('erp_user_id');

    $arr = array("PP_ID" => $user_id);
    $result["user_details"] = $this->crudModel->select("pp_user_login", $arr, "PP_ID", "ASC");

    $arr = array("PP_ID>=" => 1);
    $result["user"] = $this->crudModel->select("pp_user_login", $arr, "PP_ID", "ASC");

    $result['title'] = 'Users';

    echo view('header', $result);
    echo view('user/user_view');
    echo view('footer');
}


	public function add()
	{
		$session = session();

		// if (! $session->get('erp_user_id')) {
		// 	return redirect()->to('auth/logout');
		// }

		$db = Database::connect();

		$arr = array("PP_ID" => 1);
		$result["settings"] = $this->crudModel->select("pp_settings", $arr, "PP_ID", "ASC");

		$user_id = $this->session->get('erp_user_id');

		$arr = array("PP_ID" => $user_id);
		$result["user_details"] = $this->crudModel->select("pp_users_master", $arr, "PP_ID", "ASC");

		$result['auth'] = $db->table('pp_menu_master')
			->where('PP_ID >=', 1)
			->orderBy('PP_ID', 'ASC')
			->get()
			->getResultArray();

		$result["sub_menu_auth"] = $db->table('pp_submenu_master')
			->where('PP_ID >=', 1)
			->orderBy('PP_ID', 'ASC')
			->get()
			->getResultArray();


		$result["title"] = "Add User";

		echo view('header', $result);
		echo view('user/add_user_view', $result);
		echo view('footer');
	}

	public function insertData()
	{
		$session = session();

		// if (! $session->get('erp_user_id')) {
		// 	return redirect()->to('auth/logout');
		// }
		$db = Database::connect();

		$rules = [
			'name'             => 'required|trim'
			// 'user_name'        => 'required|trim|is_unique[pp_users_master.USERNAME]',
			// 'password'         => 'required|min_length[6]|matches[confirm_password]',
			// 'confirm_password' => 'required',
			// 'status'           => 'required'
		];


		if (! $this->validate($rules)) {
			$result['validation'] = $this->validator;
		} else {

			$encrypter = \Config\Services::encrypter();

			$password = $encrypter->encrypt(
				$this->request->getPost('password')
			);
			// encrypt($this->request->getPost('password'), config('App')->enc_dec_key);

			 $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

			$authorities = $this->request->getPost('authorities');
			$authorities = is_array($authorities)
				? implode(',', $authorities)
				: null;


			$subMenuAuth = $this->request->getPost('sub_auth_control');
			$subMenuAuth = is_array($subMenuAuth)
				? json_encode($subMenuAuth, JSON_UNESCAPED_UNICODE)
				: json_encode([]);

				
			$data = [
				'NAME'          => $this->request->getPost('name'),
				'USERNAME'     => $this->request->getPost('user_name'),
				'CONTACT_NO'    => $this->request->getPost('contact_no'),
				'EMAIL'         => $this->request->getPost('email'),
				'PASSWORD'      => $hashedPassword,
				'AUTHORIZATION' => $authorities,
				'SUB_MENU_AUTH' => $subMenuAuth,
				'STATUS'        => $this->request->getPost('status'),
				'ROLE'          => $this->request->getPost('role')
			];

			$data = esc($data);	

			$insert = $this->userModel->insert( $data);	
				
			if ($insert) {
				$session->setFlashdata(
					'message',
					'<div class="alert alert-success">User Added</div>'
				);
				return redirect()->to('User');
			}
		}
	}

	public function edit($PP_ID)
	{
		$session = session();

		// Auth check
		if (! $session->get('erp_user_id')) {
			return redirect()->to('auth/logout');
		}

		// Check user exists		
		$user_id = $this->session->get('erp_user_id');
		$arr = array("PP_ID" => $user_id);
		$userExists = $this->crudModel
			->select('users',  $arr, 'PP_ID', 'ASC');

		if (! $userExists) {
			return redirect()->to('auth/logout');
		}

		if ($this->session->get('erp_user_id')) {

			$arr = array("PP_ID" => 1);
			$result["settings"] = $this->crudModel->select("pp_settings", $arr, "PP_ID", "ASC");

			$user_id = $this->session->get('erp_user_id');

			$arr = array("PP_ID" => $user_id);
			$result["user_details"] = $this->crudModel->select("pp_users_master", $arr, "PP_ID", "ASC");

			$arr2 = array("PP_ID" => $PP_ID);
			$result["user"] = $this->crudModel->select("pp_users_master", $arr2, "PP_ID", "ASC");

			$arr3 = array("PP_ID>=" => 1);
			$result["auth"] = $this->crudModel->select("pp_menu_master", $arr3, "PP_ID", "ASC");

			$arr4 = array("PP_ID>=" => 1);
			$result["sub_menu_auth"] = $this->crudModel->select("pp_submenu_master", $arr4, "PP_ID", "ASC");

			$result["title"] = "Edit User";


			echo view('header', $result);
			echo view('user/edit_user_view');
			echo view('footer');
		} else {
			return redirect()->to('auth/logout');
		}
	}

	public function updateData($PP_ID)
	{
		$session = session();

		// Auth check
		if (! $session->get('erp_user_id')) {
			return redirect()->to('auth/logout');
		}

		$rules = [
			'name'   => 'required|trim',
			'status' => 'required'
		];

		if (! $this->validate($rules)) {

			$result['validation'] = $this->validator;
		} else {

			$authorities = $this->request->getPost('authorities') ?? [];
			$authorities = implode(',', $authorities);

			$subMenuAuth = $this->request->getPost('sub_auth_control');
			$subMenuAuth = $subMenuAuth ? json_encode($subMenuAuth) : '';

			$data = [
				'name'          => $this->request->getPost('name'),
				'authorities'   => $authorities,
				'contact_no'    => $this->request->getPost('contact_no'),
				'email'         => $this->request->getPost('email'),
				'sub_menu_auth' => $subMenuAuth,
				'status'        => $this->request->getPost('status'),
				'role'          => $this->request->getPost('role')
			];

			// XSS-safe output
			$data = esc($data);

			$condition = array("PP_ID" => $this->request->getPost('user_id'));
			$update = $this->crudModel->update("pp_users_master", $data, $condition);
			if ($update) {
				$session->setFlashdata(
					'message',
					'<div class="alert alert-success">User Updated</div>'
				);

				return redirect()->to('user');
			}
		}
	}

	public function view($PP_ID)
	{
		$session = session();

		// Auth check
		if (! $session->get('erp_user_id')) {
			return redirect()->to('auth/logout');
		}

		// Check user exists		
		$user_id = $this->session->get('erp_user_id');
		$arr = array("PP_ID" => $user_id);
		$userExists = $this->crudModel
			->select('users',  $arr, 'PP_ID', 'ASC');

		if (! $userExists) {
			return redirect()->to('auth/logout');
		}

		if ($this->session->get('erp_user_id')) {
			$arr = array("PP_ID" => 1);
			$result["settings"] = $this->crudModel->select("settings", $arr, "PP_ID", "ASC");
			$user_id = $this->session->get('erp_user_id');
			$arr = array("PP_ID" => $user_id);
			$result["user_details"] = $this->crudModel->select("pp_users_master", $arr, "PP_ID", "ASC");

			$arr = array("PP_ID" => $PP_ID);
			$result["user"] = $this->crudModel->select("pp_users_master", $arr, "PP_ID", "ASC");

			$arr = array("PP_ID>=" => 1);
			$result["auth"] = $this->crudModel->select("pp_menu_master", $arr, "PP_ID", "ASC");

			$arr = array("PP_ID>=" => 1);
			$result["sub_menu_auth"] = $this->crudModel->select("pp_submenu_master", $arr, "PP_ID", "ASC");


			$result["title"] = "View User";

			echo view('header', $result);
			echo view('user/view_user_view');
			echo view('footer');
		} else {
			redirect('auth/logout');
		}
	}

	public function del($PP_ID)
	{
		$session = session();

		// Auth check
		if (! $session->get('erp_user_id')) {
			return redirect()->to('auth/logout');
		}

		// Check user exists		
		$user_id = $this->session->get('erp_user_id');
		$arr = array("PP_ID" => $user_id);
		$userExists = $this->crudModel
			->select('users',  $arr, 'PP_ID', 'ASC');

		if (! $userExists) {
			return redirect()->to('auth/logout');
		}

		if ($this->session->get('erp_user_id')) {
			$condition = array("PP_ID" => $PP_ID);
			$delete = $this->crudModel->del("pp_users_master", $condition);
			if ($delete) {
				$session->setFlashdata(
					'message',
					'<div class="alert alert-success">User Deleted</div>'
				);

				return redirect()->to('user');
			}
		} else {
			return redirect()->to('auth/logout');
		}
	}

	public function unique_user_name()
	{
		$user_name = $this->request->getPost("user_name");
		$user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : "";

		if (!empty($user_id)) {
			$arr = array('user_name' => $user_name, "PP_ID<>" => $user_id);
		} else {
			$arr = array('user_name' => $user_name);
		}

		$arr = array("user_name" => $user_name);
		$userExists = $this->crudModel
			->select('pp_users_master',  $arr, 'PP_ID', 'ASC');

		if (! $userExists) {
			return redirect()->to('auth/logout');
		} else {
			// $this->form_validation->set_message('unique_user_name', 'This Username already exists');
			return false;
		}
		return TRUE;
	}
}
