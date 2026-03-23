<?php

namespace App\Models;

use CodeIgniter\Model;

class DealerTargetDispatchModel extends Model
{
    protected $table            = 'dp_dealer_target'; 
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
       'cust_no',
       'target',
       'dispatch',
       'updated_at',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}
