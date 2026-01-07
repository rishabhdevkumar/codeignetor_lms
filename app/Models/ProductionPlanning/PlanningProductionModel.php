<?php

namespace App\Models\ProductionPlanning;

use CodeIgniter\Model;

class PlanningProductionModel extends Model
{
    protected $table            = 'pp_production_planning_master'; 
    protected $primaryKey       = 'PP_ID';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'VERSION',
        'MACHINE',
        'SAP_MR_FG_CODE',
        'QTY_MT',
        'FROM_DATE_TIME',
        'TO_DATE_TIME',
        'UTILISED_QTY',
        'BALANCE_QTY',
        'KC1_QTY_MT',
        'KC2_QTY_MT',
        'NKC_QTY_MT',
        'KC1_UTILISED_QTY_MT',
        'KC2_UTILISED_QTY_MT',
        'NKC_UTILISED_QTY_MT',
        'KC1_BALANCE_QTY_MT',
        'KC2_BALANCE_QTY_MT',
        'NKC_BALANCE_QTY_MT',
        'CALENDAR_TYPE',
        'UPLOADED_BY',
        'UPLOADED_DATE'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}
