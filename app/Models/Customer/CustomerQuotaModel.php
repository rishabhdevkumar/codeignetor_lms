<?php

namespace App\Models\Customer;

use CodeIgniter\Model;

class CustomerQuotaModel extends Model
{
    protected $table = 'pp_customer_quota_master';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';

    protected $allowedFields = [
        'GRADE',
        'CUSTOMER_TYPE',
        'QUOTA_PERCENTAGE'
    ];

    protected $useTimestamps = false;

    // SAME PATTERN AS OTHER MODELS
    public function all_customerquota($where = [])
    {
        $builder = $this->db->table('pp_customer_quota_master as c');
        $builder->select('c.*');

        if (!empty($where)) {
            $builder->where($where);
        }

        $builder->orderBy('c.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }

    public function customerquotaid($where = [])
    {
        $builder = $this->db->table('pp_customer_quota_master as c');
        $builder->select('c.*, SUM(c2.QUOTA_PERCENTAGE) as TOTAL_QUOTA_PERCENTAGE');
        $builder->join('pp_customer_quota_master c2', 'c2.GRADE = c.GRADE', 'left');

        if (!empty($where)) {
            $builder->where($where);
        }
        $builder->groupBy('c.GRADE');
        $builder->orderBy('c.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }
}
