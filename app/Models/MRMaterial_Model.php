<?php

namespace App\Models;

use CodeIgniter\Model;

class MRMaterial_Model extends Model
{
    protected $table = 'pp_mr_material_master';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';

    protected $allowedFields = [
       'MR_MATERIAL_CODE',
	   'SAP_PLANT',
	   'GRADE',
	   'GSM',
       'DESCRIPTION', 
       'DELIVERY_PLANT_YN',
       'MACHINE_OUTPUT_KG_HR'
    ];

    protected $useTimestamps = false;

    public function all_material($whereCondition)
    {
        $builder = $this->db->table('pp_mr_material_master m');
        
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
}