<?php

namespace App\Controllers;

use App\Models\Crud_Model;
use App\Models\User_Model;
use App\Helpers\encryption;

class Authorise extends BaseController
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

		if ($this->session->get('erp_user_id')) {
			return redirect()->to('/Dashboard');
		}

		return redirect()->to('/Auth/login');
	}

	public function login()
	{
		$result = [];

		$db = \Config\Database::connect();

		// Fetch settings
		$result['settings'] = $this->crudModel->select(
			'pp_settings',
			['PP_ID' => 1],
			'PP_ID',
			'ASC'
		);

		$result['title'] = 'User Login';

		// If already logged in
		if ($this->session->get('erp_user_id')) {
			return redirect()->to('/Dashboard');
		}

		return view('login', $result);
	}

	public function attemptLogin()
	{

		$result = [];

		$db = \Config\Database::connect();

		// Fetch settings
		$result['settings'] = $this->crudModel->select(
			'pp_settings',
			['PP_ID' => 1],
			'PP_ID',
			'ASC'
		);

		$result['title'] = 'User Login';

		// If already logged in
		if ($this->session->get('erp_user_id')) {
			return redirect()->to('/Dashboard');
		}


		$rules = [
			'user_name' => 'required|trim',
			'password'  => 'required|trim',
		];

		if (! $this->validate($rules)) {
			return view('login', $result);
		}

		$userName = $this->request->getPost('user_name');
		// $password = encrypt(
		// 	$this->request->getPost('password'),
		// 	config('App')->enc_dec_key
		// );
		$encrypter = \Config\Services::encrypter();

		$password = $encrypter->encrypt(
			$this->request->getPost('password')
		);

		$credentials = [
			'USERNAME' => $userName,
			// 'PASSWORD'  => $password,
		];

		$credentials = esc($credentials);

		// Check user
		$response = $this->crudModel->select(
			'pp_users_master',
			$credentials,
			'PP_ID',
			'ASC'
		);

		if ($response) {

			// Check active & role
			$activeData = [
				'USERNAME' => $userName,
				'STATUS'    => 1,
				'ROLE<>'    => 3,
			];

			$active = $this->crudModel->select(
				'pp_users_master',
				$activeData,
				'PP_ID',
				'ASC'
			);

			if (!$active) {
				$this->session->setFlashdata(
					'message',
					"<div class='alert alert-danger'>Your Account is not Active</div>"
				);
			}

			if (!password_verify($this->request->getPost('password'), $response[0]['PASSWORD'])) {

				$this->session->setFlashdata(
					'message',
					"<div class='alert alert-danger'>Wrong Password</div>"
				);
				return redirect()->to('Auth/login');
			} else {

				// Set session
				$this->session->set('erp_user_id', $response[0]['PP_ID']);

				// $menuHtml = menu($user['id'], false);

				// $this->session->set_userdata('menu_html', $menuHtml);

				// Login log
				$loginLog = [
					'DATE_TIME'  => date('Y-m-d H:i:s'),
					'IP_ADDRESS' => $this->request->getIPAddress(),
					'STATUS'     => 'LOGGEDIN',
					'DEVICE'     => $this->request->getUserAgent()->getAgentString(),
					'USER_ID'    => $response[0]['PP_ID'],
				];

				// $this->crudModel->save('user_login', $loginLog);
				$db->table('pp_user_login')->insert($loginLog);

				$this->session->set(
					'loggedin_id',
					$this->crudModel->getInsertId()
				);
				// echo '<pre>';
				// print_r($db);
				// echo '</pre>';
				// exit;
				return redirect()->to('/Dashboard');
			}
		} else {
			$this->session->setFlashdata(
				'message',
				"<div class='alert alert-danger'>Wrong UserName</div>"
			);
		}

		// return redirect()->to('/Dashboard');
	}

	public function logout()
	{
		$db = \Config\Database::connect();

		// If user is logged in
		if ($this->session->get('erp_user_id')) {

			// Log logout activity
			$logData = [
				'date_time'  => date('Y-m-d H:i:s'),
				'ip_address' => $this->request->getIPAddress(),
				'status'     => 'LOGOUT',
				'device'     => $this->request->getUserAgent()->getAgentString(),
				'user_id'    => $this->session->get('erp_user_id'),
			];

			// $this->crudModel->save('pp_user_login', $logData);
			$db->table('pp_user_login')->insert($logData);
			// Destroy session key
			$this->session->remove('erp_user_id');

			// Optional: destroy entire session
			$this->session->destroy();
		}

		// Prevent back-button cache
		return redirect()
			->to('/Auth/login')
			->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
			->setHeader('Pragma', 'no-cache');
	}



	public function updatepwd()
	{
		// Must be logged in
		if (! $this->session->get('erp_user_id')) {
			return redirect()->to('/auth/logout');
		}

		$userId = $this->session->get('erp_user_id');

		// Load settings
		$result['settings'] = $this->crudModel->select(
			'settings',
			['id' => 1],
			'id',
			'ASC'
		);

		// Load user details
		$result['user_details'] = $this->crudModel->select(
			'users',
			['id' => $userId],
			'id',
			'ASC'
		);

		$result['title'] = 'Change';

		// Handle POST
		if ($this->request->is('post')) {

			$rules = [
				'newpass'      => 'required|trim|matches[confpassword]',
				'confpassword' => 'required|trim',
			];

			if ($this->validate($rules)) {

				$newPass  = $this->request->getPost('newpass');
				$confPass = $this->request->getPost('confpassword');

				// Encrypt passwords (legacy-compatible)
				// $newPassword  = encrypt($newPass, config('App')->enc_dec_key);
				// $confPassword = encrypt($confPass, config('App')->enc_dec_key);

				$encrypter = \Config\Services::encrypter();

				$newPassword = $encrypter->encrypt($newPass);
				$confPassword = $encrypter->encrypt($confPass);

				if ($newPass === $confPass) {

					if ($this->userModel->updatepassword($newPassword, $userId)) {

						$this->session->setFlashdata(
							'message',
							"<div class='alert alert-success'>Update Password Successfully</div>"
						);

						return redirect()->to('/dashboard');
					} else {
						echo 'Failed to Update Password.';
					}
				} else {
					echo 'Does Not Match Password.';
				}
			}
		}

		return view('change_password', $result)
			. view('footer');
	}
}
