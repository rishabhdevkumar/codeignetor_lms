<?php

namespace App\Controllers;

use App\Models\Crud_Model;
use App\Models\User_Model;
use App\Helpers\encryption;

class Auth extends BaseController
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
			return redirect()->to('/dashboard');
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

		// Handle POST
		if ($this->request->is('post')) {

			$rules = [
				'user_name' => 'required|trim',
				'password'  => 'required|trim',
			];

			if (! $this->validate($rules)) {
				return view('login', $result);
			}

			$userName = $this->request->getPost('user_name');
			$password = encrypt(
				$this->request->getPost('password'),
				config('App')->enc_dec_key
			);

			$credentials = [
				'user_name' => $userName,
				'password'  => $password,
			];

			$credentials = esc($credentials);

			// Check user
			$response = $this->crudModel->select(
				'users',
				$credentials,
				'id',
				'ASC'
			);

			if ($response) {

				// Check active & role
				$activeData = [
					'user_name' => $userName,
					'password'  => $password,
					'status'    => 1,
					'role<>'    => 3,
				];

				$active = $this->crudModel->select(
					'users',
					$activeData,
					'id',
					'ASC'
				);

				if ($active) {

					// Set session
					$this->session->set('erp_user_id', $response[0]['id']);

					// Login log
					$loginLog = [
						'date_time'  => date('Y-m-d H:i:s'),
						'ip_address' => $this->request->getIPAddress(),
						'status'     => 'LOGGEDIN',
						'device'     => $this->request->getUserAgent()->getAgentString(),
						'user_id'    => $response[0]['id'],
					];

					// $this->crudModel->save('user_login', $loginLog);
					$db->table('user_login')->insert($loginLog);

					$this->session->set(
						'loggedin_id',
						$this->crudModel->getInsertId()
					);

					return redirect()->to('/Dashboard');
				} else {
					$this->session->setFlashdata(
						'message',
						"<div class='alert alert-danger'>Your Account is not Active</div>"
					);
				}
			} else {
				$this->session->setFlashdata(
					'message',
					"<div class='alert alert-danger'>Wrong UserName / Password</div>"
				);
			}
		}

		return view('login', $result);
	}


	public function logout()
	{
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

			$this->crudModel->save('user_login', $logData);

			// Destroy session key
			$this->session->remove('erp_user_id');

			// Optional: destroy entire session
			// $this->session->destroy();
		}

		// Prevent back-button cache
		return redirect()
			->to('/auth/login')
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
				$newPassword  = encrypt($newPass, config('App')->enc_dec_key);
				$confPassword = encrypt($confPass, config('App')->enc_dec_key);

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
