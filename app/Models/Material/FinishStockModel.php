<?php

namespace App\Models\Material;

use CodeIgniter\Model;

class FinishStockModel extends Model
{
    protected $table = 'pp_finish_stock';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';

    protected $allowedFields = [
        'FINISH_MATERIAL_CODE',
        'SAP_PLANT',
        'STOCK_QTY',
        'BALANCE_QTY'
    ];

    protected $useTimestamps = false;

    // Get all finish stock or filtered data
    public function all_finishstock($where = [])
    {
        $builder = $this->db->table($this->table . ' f');
        $builder->select('f.*');

        if (!empty($where)) {
            $builder->where($where);
        }

        $builder->orderBy('f.PP_ID', 'DESC');

        $query = $builder->get();

        return ($query->getNumRows() > 0) ? $query->getResultArray() : false;
    }
}
