<?php

namespace App\Models\Customer;

use CodeIgniter\Model;
use Config\Database;

class CustomerModel extends Model
{
    protected $table = 'vtiger_bp_customer_master';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'cust_no',
        'cust_name',
        'price_list_no',
        'parent_cust_no',
        'currency',
        'm_target',
        'dispatch'
    ];

    protected $useTimestamps = false;

    public function all_customer($whereCondition)
    {
        $builder = $this->db->table('pp_customer_master m');

        $builder->select('
            m.*
        ');

        if (!empty($whereCondition)) {
            $builder->where($whereCondition);
        }

        $builder->orderBy('m.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }

    public function saveCustomer(array $customerData, array $vtigerData, int $customerId )
    {
        $db = Database::connect();
        $db->transBegin();

        try {

            if ($customerId === null) {

                $db->table($this->table)->insert($customerData);
                $customerId = $db->insertID();

                if (!$customerId) {
                    throw new \Exception('Customer insert failed');
                }

            } else {

                $db->table($this->table)
                   ->where($this->primaryKey, $customerId)
                   ->update($customerData);

                if ($db->affectedRows() < 0) {
                    throw new \Exception('Customer update failed');
                }
            }

            $vtigerTable = 'vtiger_bp_customer_master';

            $vtigerData['cust_no'] = $customerData['CUSTOMER_CODE'];

            $exists = $db->table($vtigerTable)
                         ->where('cust_no', $vtigerData['cust_no'])
                         ->countAllResults();

            if ($exists) {
                $db->table($vtigerTable)
                   ->where('cust_no', $vtigerData['cust_no'])
                   ->update($vtigerData);
            } else {
                $db->table($vtigerTable)->insert($vtigerData);
            }

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            $db->transCommit();
            return $customerId;

        } catch (\Throwable $e) {

            $db->transRollback();
            log_message('error', 'Customer Save Error: ' . $e->getMessage());
            return false;
        }
    }

    public function getCustomers(array $filters = [], int $limit = 50, int $offset = 0)
    {
        return $this->db->table('pp_customer_master m')
            ->select([
                'm.PP_ID',
                'm.CUSTOMER_CODE',
                'v.cust_name',
                'm.CUSTOMER_TYPE',
                'm.PIN_CODE',
                'm.STATE',
                'v.parent_cust_no',
            ])
            ->join(
                'vtiger_bp_customer_master v',
                'v.cust_no = m.CUSTOMER_CODE',
                'left'
            )
            ->where($filters)
            ->orderBy('m.PP_ID', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
