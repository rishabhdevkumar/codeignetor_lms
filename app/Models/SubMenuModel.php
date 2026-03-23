<?php
namespace App\Models;

use CodeIgniter\Model;

class SubMenuModel extends Model
{
    protected $table = 'pp_submenu_master';
    protected $primaryKey = 'PP_ID';
    protected $returnType = 'array';

    protected $allowedFields    = [
		'PP_ID',
		'ORDER_ID',
        'SUB_MENU1',
        'SUB_MENU2',
        'SUB_MENU3',
        'CONTROLLER',
        'ROUTES',
	];
}
