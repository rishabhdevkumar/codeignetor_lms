<?php

namespace App\Models\OrderGeneration;

use CodeIgniter\Model;

class IndentModel extends Model
{
    protected $table = 'vtiger_bp_placed_order_header';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'in_no', 'in_date', 'sold_to_code', 'bill_to_code', 'ship_to_code',
        'market_segment', 'order_type', 'po_no', 'sap_init'
    ];
}
