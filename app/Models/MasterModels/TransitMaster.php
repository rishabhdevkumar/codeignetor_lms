<?php

namespace App\Models\MasterModels;

use CodeIgniter\Model;

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
}
