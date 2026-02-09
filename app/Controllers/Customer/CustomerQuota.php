<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\Crud_Model;
use App\Models\Customer\CustomerQuotaModel;
use App\Models\Material\MRMaterialModel;

class CustomerQuota extends BaseController
{
    protected $session;
    protected $crudModel;
    protected $customerQuotaModel;
    protected $materialModel;
    protected $helpers = ['url', 'form', 'security'];

    public function __construct()
    {
        $this->session = session();
        $this->crudModel = new Crud_Model();
        $this->customerQuotaModel = new CustomerQuotaModel();
        $this->materialModel  = new MRMaterialModel();

        date_default_timezone_set('Asia/Calcutta');
    }

    public function index()
    {
        $result = [];
        $result['title'] = "Customer Quota";

        $where = [];
        $result['customerquota'] = $this->customerQuotaModel->all_customerquota($where);

        echo view('header', $result);
        echo view('customerquota/customerquota_view', $result);
        echo view('footer');
    }

    public function add()
    {
        $data['title'] = "Add Customer Quota";

        $data['grade'] = $this->materialModel
            ->select('GRADE')
            ->distinct()
            ->where('GRADE IS NOT NULL')
            ->orderBy('GRADE', 'ASC')
            ->findAll();

        echo view('header', $data);
        echo view('customerquota/add_customerquota_view', $data);
        echo view('footer');
    }

    public function insertData()
    {
        $arr = [
            'GRADE'            => trim($this->request->getPost('grade')),
            'CUSTOMER_TYPE'    => $this->request->getPost('customer_type'),
            'QUOTA_PERCENTAGE' => $this->request->getPost('quota_percentage'),
        ];

        $checkArr = [
            'GRADE' => trim($this->request->getPost('grade')),
            'CUSTOMER_TYPE' => $this->request->getPost('customer_type'),
        ];

        $result = $this->customerQuotaModel->all_customerquota($checkArr);

        $data['title'] = "Add Customer Quota";

        $data['grade'] = $this->materialModel
            ->select('GRADE')
            ->distinct()
            ->where('GRADE IS NOT NULL')
            ->orderBy('GRADE', 'ASC')
            ->findAll();

        if (!$result) {
            $insert = $this->crudModel->saveData('pp_customer_quota_master', $arr);
        } else {
            $data['error'] = "Customer Quota Already Exists!";
            return view('header', $data)
                . view('customerquota/add_customerquota_view', $data)
                . view('footer');
        }

        if ($insert) {
            return redirect()->to('/CustomerQuota')->with('success', 'Customer Quota Added Successfully');
        }
    }

    public function edit($id)
    {
        $arr = ['c.PP_ID' => $id];
        $dataList = $this->customerQuotaModel->customerquotaid($arr);

        $result['customerquota'] = $dataList[0];

        $result['grade'] = $this->materialModel
            ->select('GRADE')
            ->distinct()
            ->where('GRADE IS NOT NULL')
            ->orderBy('GRADE', 'ASC')
            ->findAll();

        if (!$result['customerquota']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Customer Quota not found");
        }

        $result['title'] = "Edit Customer Quota";

        echo view('header', $result);
        echo view('customerquota/edit_customerquota_view', $result);
        echo view('footer');
    }

    public function updateData()
    {
        $data = [
            'GRADE'            => trim($this->request->getPost('grade')),
            'CUSTOMER_TYPE'    => $this->request->getPost('customer_type'),
            'QUOTA_PERCENTAGE' => $this->request->getPost('quota_percentage'),
        ];

        $condition = ['PP_ID' => $this->request->getPost('pp_id')];

        $this->crudModel->updateData('pp_customer_quota_master', $data, $condition);

        return redirect()->to('/CustomerQuota')->with('success', 'Customer Quota Updated');
    }

    public function view($id)
    {
        $arr = ['c.PP_ID' => $id];
        $dataList = $this->customerQuotaModel->customerquotaid($arr);

        $result['customerquota'] = $dataList[0];

        if (!$result['customerquota']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Customer Quota not found");
        }

        $result['title'] = "View Customer Quota";

        echo view('header', $result);
        echo view('customerquota/view_customerquota_view', $result);
        echo view('footer');
    }

    public function delete($id)
    {
        $this->crudModel->del('pp_customer_quota_master', ['PP_ID' => $id]);
        return redirect()->to('/CustomerQuota')->with('success', 'Customer Quota Deleted');
    }

    public function getAllotmentQuota()
    {
        $grade = $this->request->getPost('grade');

        $builder = $this->customerQuotaModel->select('CUSTOMER_TYPE,QUOTA_PERCENTAGE')->groupBy('GRADE');

        if (!empty($grade)) {
            $builder->where('GRADE', $grade);
        }

        return $this->response->setJSON($builder->findAll());
    }
}
