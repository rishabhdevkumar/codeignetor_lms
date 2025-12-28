<?php

namespace App\Models;

use CodeIgniter\Model;

class Material_Model extends Model
{
    protected $table = 'pp_finish_material_master';
    protected $primaryKey = 'ID';
    protected $returnType = 'array';

    protected $allowedFields = [
       'FINISH_MATERIAL_CODE',
	   'SAP_PLANT',
	   'GRADE',
	   'GSM',
       'UOM',
       'ITEM_TYPE',
       'WIDTH',
       'LENGTH',
       'MR_MATERIAL_CODE',
       'PACKAGING_TIME',
       'DESCRIPTION'
    ];

    protected $useTimestamps = false;

    public function all_material($whereCondition)
    {
        $builder = $this->db->table('pp_finish_material_master m');
        
        $builder->select('
            m.*
        ');

        if (!empty($whereCondition)) {
            $builder->where($whereCondition);
        }

        $builder->orderBy('m.ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }
}