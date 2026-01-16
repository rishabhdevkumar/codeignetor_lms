<?php

namespace App\Models\ProductionPlanning;

use CodeIgniter\Model;

class PpCalendarApprovalModel extends Model
{
    protected $table = 'pp_calendar_approval';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';

    protected $allowedFields = [
        'PP_ID',
        'PLANNING_CAL_ID',
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
        'APPROVAL_STATUS',
        'UPLOADED_BY',
        'UPLOADED_DATE',
        'ACTION_BY',
        'ACTION_DATE'
    ];
}
