<?php

namespace App\Models\Machine;

use CodeIgniter\Model;

class MachineModel extends Model
{
    protected $table = 'pp_machine_master';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';

    protected $allowedFields = [
       'MACHINE_TPM_ID',
       'DESCRIPTION',
       'TYPE',
       'PIN_CODE',   
	   'SAP_PLANT',
       'SAP_VENDOR_CODE',
       'CAPACITY_PER_DAY_MT',
	   'FINISH_LOSS_PERCENT',
       'GRADE_CHANGE_TIME_MIN',
       'GSM_CHANGE_TIME_MIN'
    ];

    protected $useTimestamps = false;

    public function all_machine($whereCondition)
    {
        $builder = $this->db->table('pp_machine_master m');
        
        $builder->select('
            m.*
        ');

        if (!empty($whereCondition)) {
            $builder->where($whereCondition);
        }

        $builder->orderBy('m.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }
}