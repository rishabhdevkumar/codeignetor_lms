<?php

namespace App\Controllers\Material;

use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use App\Models\Crud_Model;
use App\Models\Material\FinishStockModel;

class FinishStock extends BaseController
{
    protected $session;
	protected $crudModel;
	protected $finishstockModel;
	protected $helpers = ['url', 'form', 'security'];

    public function __construct()
	{
		$this->session        = session();
		$this->crudModel      = new Crud_Model();
		$this->finishstockModel  = new FinishStockModel();

		date_default_timezone_set('Asia/Calcutta');
	}

    public function index()
    {
       $result = [];

		// Page title
		$result['title'] = "Finish Stock";

		// Finish stock list
		$where = [];   // previously $data
		$result['finishstock'] = $this->finishstockModel->all_finishstock($where);
		// echo "<pre>";print_R($result);die();

		// Render views in CI4
		echo view('header', $result);
		echo view('finishstock/finishstock_view', $result);
		echo view('footer');
    }

    public function add()
    {
        $data['title'] = "Add Finish Stock";

        echo view('header', $data);
        echo view('finishstock/add_finishstock_view', $data);
        echo view('footer');
    }

    public function insertData()
	{
			$arr = [
				'FINISH_MATERIAL_CODE' => $this->request->getPost('finish_material_code'),
                'SAP_PLANT'            => $this->request->getPost('sap_plant'),
                'STOCK_QTY'            => $this->request->getPost('stock_qty'),
                'BALANCE_QTY'          => $this->request->getPost('balance_qty'),
			];

			$arr2 = array(
				'FINISH_MATERIAL_CODE' => $this->request->getPost('finish_material_code'),
				'SAP_PLANT'        => $this->request->getPost('sap_plant')
			);
			$result["finishstock"] = $this->finishstockModel->all_finishstock($arr2);

			if (!$result['finishstock']) {
				$insert = $this->crudModel->saveData('pp_finish_stock', $arr);
			} else {
				$result['error'] = "Stock Already Exist!";
				return view('header', $result)
					. view('finishstock/add_finishstock_view', $result)
					. view('footer');
			}

			if ($insert) {
				return redirect()->to('/FinishStock')->with('success', 'Stock Created successfully');
			}

	}

    public function edit($id)
    {

        $arr = array('PP_ID' => $id);
		$dataList = $this->finishstockModel->all_finishstock($arr);

		$result["finishstock"] = $dataList[0];

		if (!$result['finishstock']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Stock not found");
		}

		$result["title"] = "Edit Finish Stock";

		echo view('header', $result);
		echo view('finishstock/edit_finishstock_view', $result);
		echo view('footer');

    }

    public function updateData()
    {
        $data = [
            'FINISH_MATERIAL_CODE' => $this->request->getPost('finish_material_code'),
            'SAP_PLANT'            => $this->request->getPost('sap_plant'),
            'STOCK_QTY'            => $this->request->getPost('stock_qty'),
            'BALANCE_QTY'          => $this->request->getPost('balance_qty'),
        ];

        $condition = ['PP_ID' => $this->request->getPost('finishstock_id')];
		$update = $this->crudModel->updateData('pp_finish_stock', $data, $condition);
        // $this->crudModel->updateData('pp_finish_stock', $data, $condition);

        return redirect()->to('/FinishStock')->with('success', 'Stock Updated');
    }

	public function view($id)
	{
		$arr = array('PP_ID' => $id);
		$dataList = $this->finishstockModel->all_finishstock($arr);

		$result["finishstock"] = $dataList[0];

		if (!$result['finishstock']) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Stock not found");
		}

		$result["title"] = "View Stock";

		echo view('header', $result);
		echo view('finishstock/view_finishstock_view', $result);
		echo view('footer');
	}

    public function delete($id)
    {
        $this->crudModel->del('pp_finish_stock', ['PP_ID' => $id]);
        return redirect()->to('/FinishStock')->with('success', 'Stock Deleted');
    }

}
