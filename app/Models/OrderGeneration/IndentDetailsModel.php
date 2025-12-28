<?php

namespace App\Models\OrderGeneration;

use CodeIgniter\Model;

class IndentDetailsModel extends Model
{
    protected $table = 'vtiger_bp_placed_order_detail';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'in_no', 'item_type', 'item_variety', 'gsm', 'request_date',
        'material_code', 'line_item', 'sap_init',
        'quantity'
    ];
}
