<?php

namespace App\Models\OrderGeneration;

use CodeIgniter\Model;

class IndentAllotmentModel extends Model
{
    protected $table = 'pp_indent_allotment';   

    protected $primaryKey = 'PP_ID';

    protected $allowedFields = [
        'INDENT_NO',
        'INDENT_LINE_ITEM',
        'PLANNING_CAL_ID',
        'VERSION',
        'FINISH_MATERIAL_CODE',
        'MR_MATERIAL_CODE',
        'QUANTITY',
        'FROM_DATE',
        'TO_DATE',
        'FINISHING_DATE',
        'DOOR_STEP_DEL_DATE',
        'CUSTOMER_TYPE',
        'SAP_ORDER_NO',
        'SAP_REMARKS'
    ];

    protected $useTimestamps = false;
}
