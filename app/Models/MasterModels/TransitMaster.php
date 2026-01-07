<?php

namespace App\Models\MasterModels;

use CodeIgniter\Model;
use Config\Database;

class TransitMaster extends Model
{
    protected $table            = 'pp_transit_master';
    protected $primaryKey       = 'PP_ID';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'FROM_COUNTRY',
        'FROM_PINCODE',
        'TO_COUNTRY',
        'TO_PINCODE',
        'DISTANCE',
        'TRANSIT_TIME'
    ];

    protected $useTimestamps = false;

    public function all_customerstransit($where = [])
    {
        $builder = $this->db->table('pp_transit_master as t');
        $builder->select('t.*');

        if (!empty($where)) {
            $builder->where($where);
        }

        $builder->orderBy('t.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }
}
