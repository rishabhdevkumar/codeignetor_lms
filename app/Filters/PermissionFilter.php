<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Database;

class PermissionFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        $permissions = $session->get('permissions');

        if (!$permissions) {
            return redirect()->to('/login');
        }

        $uri = service('uri');

        $controller = $uri->getSegment(1);
        $method     = $uri->getSegment(2) ?: 'index';

        $db = Database::connect();

        /*
        |----------------------------------
        | Find submenu by route
        |----------------------------------
        */
        $submenu = $db->table('pp_submenu_master')
            ->where('ROUTES', $controller.'/'.$method)
            ->orWhere('ROUTES', $controller)
            ->get()
            ->getRowArray();

        if (!$submenu) {
            return;
        }

        $menu_id = $submenu['PP_ID'];

        if (!isset($permissions[$menu_id])) {
            return redirect()->to('/unauthorized');
        }

        /*
        |----------------------------------
        | Method Permission Mapping
        |----------------------------------
        */

        $map = [
            'index' => 'index',
            'add' => 'add',
            'save' => 'add',
            'create' => 'add',
            'edit' => 'edit',
            'update' => 'edit',
            'view' => 'view',
            'show' => 'view'
        ];

        $action = $map[$method] ?? 'index';

        if (!in_array($action, $permissions[$menu_id])) {
            return redirect()->to('/unauthorized');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}