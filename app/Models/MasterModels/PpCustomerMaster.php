<?php

namespace App\Models\MasterModels;

use CodeIgniter\Model;

class PpCustomerMaster extends Model
{
    protected $table            = 'pp_customer_master';
    protected $primaryKey       = 'PP_ID';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'CUSTOMER_CODE',
        'CUSTOMER_TYPE',
        'COUNTRY',
        'PIN_CODE',
        'STATE'
    ];

    protected $useTimestamps = false;
}
