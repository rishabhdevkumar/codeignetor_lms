<?php

namespace App\Models\Customer;

use CodeIgniter\Model;

class CustomerTransitModel extends Model
{
    protected $table            = 'pp_transit_master'; 
    protected $primaryKey       = 'PP_ID';
    // protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;

    protected $allowedFields    = [
       'FROM_COUNTRTY',
       'FROM_PINCODE',
       'TO_COUNTRY',
       'TO_PINCODE',
       'DISTANCE',
       'TRANSIT_TIME'
    ];

    protected $useTimestamps = false;

    // protected $validationRules = [];
    // protected $validationMessages = [];
    // protected $skipValidation = false;

    public function all_customertransit($whereCondition)
    {
        $builder = $this->db->table('pp_transit_master t');

        $builder->select('
            t.*
        ');

        if (!empty($whereCondition)) {
            $builder->where($whereCondition);
        }

        $builder->orderBy('t.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }

}
