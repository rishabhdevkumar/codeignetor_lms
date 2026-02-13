<?php
namespace App\Models;

use CodeIgniter\Model;

class SubMenuModel extends Model
{
    protected $table = 'pp_submenu_master';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';
}
