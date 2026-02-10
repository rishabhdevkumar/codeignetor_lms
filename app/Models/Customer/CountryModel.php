<?php

namespace App\Models\Customer;

use CodeIgniter\Model;

class CountryModel extends Model
{
    protected $table = 'pp_country_master';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';
    protected $allowedFields = ['COUNTRY_ID', 'COUNTRY_NAME'];
    protected $useTimestamps = false;

    public function getActiveCountries()
    {
        return $this->where('STATUS', '1')
         ->orderBy('COUNTRY_NAME', 'ASC')
        ->findAll();
    }
}
