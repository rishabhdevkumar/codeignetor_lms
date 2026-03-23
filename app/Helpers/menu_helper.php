<?php

use Config\Database;

if (!function_exists('menu')) {

    function menu($user_id)
    {
        helper('url');

        /*
    |-----------------------------
    | Check Cache
    |-----------------------------
    */

        // $cache = cache();
        // $cache_key = 'menu_' . $user_id;

        // if ($menu = $cache->get($cache_key)) {
        //     return $menu;
        // }

        $db = Database::connect();

        /*
    |-----------------------------
    | USER PERMISSIONS
    |-----------------------------
    */
        $user = $db->table('pp_users_master')
            ->select('AUTHORIZATION, SUB_MENU_AUTH')
            ->where('PP_ID', $user_id)
            ->get()
            ->getRowArray();

        if (!$user) {
            return '';
        }

        $authorities = array_filter(explode(',', $user['AUTHORIZATION']));

        $menu_control = json_decode(html_entity_decode($user['SUB_MENU_AUTH']), true);

        $submenu_ids = is_array($menu_control) ? array_keys($menu_control) : [0];

        /*
    |-----------------------------
    | MAIN MENUS
    |-----------------------------
    */
        $menus = $db->table('pp_menu_master')
            ->whereIn('ORDER_ID', $authorities)
            ->orderBy('ORDER_ID', 'ASC')
            ->get()
            ->getResultArray();

        /*
    |-----------------------------
    | SUB MENUS
    |-----------------------------
    */
        $submenus = $db->table('pp_submenu_master')
            ->whereIn('PP_ID', $submenu_ids)
            ->orderBy('ORDER_ID', 'ASC')
            ->get()
            ->getResultArray();

        /*
    |-----------------------------
    | GROUP BY ORDER_ID
    |-----------------------------
    */
        $group = [];

        foreach ($submenus as $sm) {
            $group[$sm['ORDER_ID']][] = $sm;
        }

        $html = '';

        foreach ($menus as $menu) {

            $menu_id   = $menu['ORDER_ID'];
            $menu_name = esc($menu['MENU_NAME']);

            $menu_subs = $group[$menu_id] ?? [];

            /*
        |-----------------------------
        | SINGLE SUBMENU
        |-----------------------------
        */
            if (count($menu_subs) == 1) {

                $route = $menu_subs[0]['ROUTES'];

                $html .= '
            <li class="nav-item mt-2">
                <a class="nav-link" href="' . base_url($route) . '">
                    ' . $menu_name . '
                </a>
            </li>';
            } else {

                /*
            |-----------------------------
            | DROPDOWN MENU
            |-----------------------------
            */

                $html .= '
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    ' . $menu_name . '
                </a>
                <ul class="dropdown-content">';

                foreach ($menu_subs as $sub) {

                    $title = $sub['SUB_MENU2'];
                    $url   = base_url($sub['ROUTES']);

                    $html .= '
                <li>
                    <a class="dropdown-item" href="' . $url . '">
                        ' . esc($title) . '
                    </a>
                </li>';
                }

                $html .= '</ul></li>';
            }
        }

        // SAVE CACHE
        // $cache->save($cache_key, $html, 3600);

        return $html;
    }
}
