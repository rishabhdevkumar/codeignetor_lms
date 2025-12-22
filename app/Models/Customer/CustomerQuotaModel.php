<?php

namespace App\Models\CustomerQuota;

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

    public function all_customer($whereCondition)
    {
        $builder = $this->db->table('pp_customer_quota_master c');

        $builder->select('
            c.*
        ');

        if (!empty($whereCondition)) {
            $builder->where($whereCondition);
        }

        $builder->orderBy('c.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }

}
