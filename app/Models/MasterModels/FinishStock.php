<?php

namespace App\Models\MasterModels;

use CodeIgniter\Model;

class FinishStock extends Model
{
    protected $table            = 'pp_finish_stock';
    protected $primaryKey       = 'PP_ID';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'FINISH_MATERIAL_CODE',
        'SAP_PLANT',
        'STOCK_QTY',
        'BALANCE_QTY'
    ];

    protected $useTimestamps = false;
}
