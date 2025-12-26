<?php

namespace App\Controllers\Material;

use App\Controllers\BaseController;
use App\Models\Crud_Model;
use App\Models\Material\FinishStockModel;

class FinishStock extends BaseController
{
    protected $session;
    protected $crudModel;
    protected $finishstockModel;

    public function __construct()
    {
        $this->session = session();
        $this->crudModel = new Crud_Model();
        $this->finishstockModel = new FinishStockModel();
    }

    public function index()
    {
        $data['title'] = "Finish Stock";
        $data['finishstock'] = $this->finishstockModel->all_finish_stock([]);

        echo view('header', $data);
        echo view('finishstock/finishstock_view', $data);
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
        $data = [
            'FINISH_MATERIAL_CODE' => $this->request->getPost('finish_material_code'),
            'SAP_PLANT'            => $this->request->getPost('sap_plant'),
            'STOCK_QTY'            => $this->request->getPost('stock_qty'),
            'BALANCE_QTY'          => $this->request->getPost('balance_qty'),
        ];

        $check = [
            'FINISH_MATERIAL_CODE' => $this->request->getPost('finish_material_code'),
            'SAP_PLANT'            => $this->request->getPost('sap_plant')
        ];

        $exist = $this->finishstockModel->all_finish_stock($check);

        if ($exist) {
            return redirect()->back()->with('error', 'Stock already exists!');
        }

        $this->crudModel->saveData('pp_finish_stock', $data);
        return redirect()->to('/FinishStock')->with('success', 'Stock Added Successfully');
    }

    public function edit($id)
    {
        $data['finishstock'] = $this->finishstockModel->all_finish_stock(['PP_ID' => $id])[0] ?? null;

        if (!$data['finishstock']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Stock not found");
        }

        $data['title'] = "Edit Finish Stock";

        echo view('header', $data);
        echo view('finishstock/edit_finishstock_view', $data);
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

        $this->crudModel->updateData('pp_finish_stock', $data, $condition);

        return redirect()->to('/FinishStock')->with('success', 'Stock Updated');
    }

    public function delete($id)
    {
        $this->crudModel->del('pp_finish_stock', ['PP_ID' => $id]);
        return redirect()->to('/FinishStock')->with('success', 'Stock Deleted');
    }
}
