<?php

namespace App\Models\Machine;

use CodeIgniter\Model;

class MachineAvailabilityModel extends Model
{
    protected $table = 'pp_machine_availability';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';

    protected $allowedFields = [
        'MACHINE_TPM_ID',
        'SAP_NOTIFICATION_NO',
        'TYPE',
        'FROM_DATE',
        'TO_DATE'
    ];

    protected $useTimestamps = false;

    public function all_machine_availability($whereCondition = [])
    {
        $builder = $this->db->table('pp_machine_availability ma');

        $builder->select('
            ma.*
        ');

        if (!empty($whereCondition)) {
            $builder->where($whereCondition);
        }

        $builder->orderBy('ma.PP_ID', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }
}
