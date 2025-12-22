<?php

namespace App\Models\Material;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'pp_finish_stock';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';

    protected $allowedFields = [
       'FINISH_MATERIAL_CODE',
	   'SAP_PLANT',
	   'STOCK_QTY',
       'BALANCE_QTY',
    ];

    protected $useTimestamps = false;

    public function all_material($whereCondition)
    {
        $builder = $this->db->table('pp_finish_stock f');
        
        $builder->select('
            f.*
        ');

        if (!empty($whereCondition)) {
            $builder->where($whereCondition);
        }

        $builder->orderBy('f.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }
}