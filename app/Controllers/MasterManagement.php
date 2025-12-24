<?php

namespace App\Controllers;

use App\Models\Crud_Model;
use CodeIgniter\Controller;

class MasterManagement extends BaseController
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
            
			// $arr=array("id"=>1);
		    // $result["settings"]=$this->Crud_Model->select("settings",$arr,"id","ASC");
			// $user_id=$this->session->userdata('erp_user_id');
			// $arr=array("id"=>$user_id);
			// $result["user_details"]=$this->Crud_Model->select("users",$arr,"id","ASC");
			$result["title"]="Master Management";
									
				echo view('header', $result);
		        echo view('mastermanagement', $result);
		        echo view('footer');	
		
	}
	
	
}
?>	