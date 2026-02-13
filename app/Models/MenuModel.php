<?php
namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'pp_menu_master';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';
}
