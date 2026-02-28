<?php

namespace App\Models\IndentAllotment;

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
        'PACKAGING_TIME',
        'TRANSIT_TIME',
        'CUSTOMER_TYPE',
        'CALENDAR_TYPE',
        'PO_NO',
        'PO_LINE_ITEM',
        'SCHEDULE_LINE_ITEM',
        'FULFILLMENT_FLAG',
        'SAP_ORDER_NO',
        'SAP_REMARKS',
        'MODIFICATION_FLAG',
        'SAP_ORDER_CHANGE',
        'OLD_FROM_DATE',
        'OLD_TO_DATE',
        'OLD_FINISHING_DATE',
        'OLD_DOOR_STEP_DEL_DATE',
        'REMARKS'
    ];

    protected $useTimestamps = false;

    public function all_indents($whereCondition)
    {
        $builder = $this->db->table('pp_indent_allotment i');
        
        $builder->select('
            i.*
        ');
        if (!empty($whereCondition)) {
            $builder->where($whereCondition);
        }

        $builder->orderBy('i.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }

    public function notAllotedIndents($whereCondition)
    {
        $builder = $this->db->table('pp_indent_allotment i');
        
        $builder->select('
            i.*,c.cust_name
        ');
        $builder->join('vtiger_bp_placed_order_header h','h.in_no = i.INDENT_NO');
        $builder->join('vtiger_bp_customer_master c','c.cust_no = h.bill_to_code');
        if (!empty($whereCondition)) {
            $builder->where($whereCondition);
        }

        $builder->orderBy('i.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }
}
