<?php

namespace App\Models\ProductionPlanning;

use CodeIgniter\Model;

class PpMachineAvailabilityModel extends Model
{
    protected $table      = 'pp_machine_availability';
    protected $primaryKey = 'PP_ID';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'MACHINE_TPM_ID',
        'SAP_NOTIFICATION_NO',
        'TYPE',
        'FROM_DATE',
        'TO_DATE',
        'UPDATED_BY',
        'UPDATED_DATE',
        'PROCESS_DATE_TIME'
    ];

    // Optional but recommended
    protected $useTimestamps = false;

    protected $createdField  = '';
    protected $updatedField  = '';
}
