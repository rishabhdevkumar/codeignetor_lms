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
        //    'FROM_COUNTRY_NAME'
    ];

    protected $useTimestamps = false;

    // protected $validationRules = [];
    // protected $validationMessages = [];
    // protected $skipValidation = false;

    public function all_customerstransit($whereCondition)
    {
        $builder = $this->db->table('pp_transit_master t');

        $builder->select('
            t.*,
            c1.COUNTRY_NAME AS FROM_COUNTRY_NAME,
            c2.COUNTRY_NAME AS TO_COUNTRY_NAME
        ');
        $builder->join('pp_country_master c1', 'c1.COUNTRY_ID = t.FROM_COUNTRY', 'left');
        $builder->join('pp_country_master c2', 'c2.COUNTRY_ID = t.TO_COUNTRY', 'left');

        if (is_array($whereCondition) && !empty($whereCondition)) {
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
