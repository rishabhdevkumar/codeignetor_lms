<?php

namespace App\Models;

use CodeIgniter\Model;

class User_Model extends Model
{

	protected $table = 'pp_users_master';
	protected $primaryKey = 'PP_ID';
	protected $returnType = 'array';

	protected $allowedFields    = [
		'PP_ID',
		'NAME',
		'USERNAME',
		'CONTACT_NO',
		'EMAIL',
		'PASSWORD',
		'AUTHORIZATION',
		'SUB_MENU_AUTH',
		'STATUS',
		'ROLE'
	];


	public function getCurrentPass(int $userId)
	{
		return $this->where('PP_ID', $userId)->first();
	}


	public function updatepassword(string $password, int $userId): bool
	{
		return $this->db->table('pp_users_master')
			->where('PP_ID', $userId)
			->update(['PASSWORD' => $password]);
	}
}
