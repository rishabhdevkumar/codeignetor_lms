function menu($user_id)
{
$ci =& get_instance();
$ci->load->database();

// Secure query binding
$user = $ci->db
->where('PP_ID', $user_id)
->get('pp_users_master')
->row_array();

if (!$user) {
redirect('auth/logout');
return;
}

$authorities = $user['AUTHORIZATION'];
$menuControl = json_decode($user['SUB_MENU_AUTH'], true);

if (empty($authorities)) {
return;
}

$subMenuAuth = '0';
if (is_array($menuControl) && !empty($menuControl)) {
$subMenuAuth = implode(",", array_keys($menuControl));
}

// Get main menus
$menus = $ci->db
->where_in('ORDER_ID', explode(',', $authorities))
->order_by('ORDER_ID', 'ASC')
->get('MENUS')
->result_array();

if (!$menus) {
return;
}

$menu_tree = '';

foreach ($menus as $menu) {

$subMenus = $ci->db
->where_in('PP_ID', explode(',', $subMenuAuth))
->where('ORDER_ID', $menu['ORDER_ID'])
->order_by('ORDER_ID', 'ASC')
->get('SUB_MENUS')
->result_array();

if (!$subMenus) continue;

$menu_tree .= '<li>
    <a href="#"><i class=""></i>'.$menu["MENU_NAME"].'<span class="fa arrow"></span></a>
    <ul class="nav collapse">';

        $grouped = [];

        foreach ($subMenus as $item) {
        $grouped[$item['SUB_MENU1']][$item['SUB_MENU2']][] = [
        'SUB_MENU3' => $item['SUB_MENU3'],
        'CONTROLLER'=> $item['CONTROLLER']
        ];
        }

        foreach ($grouped as $sm1 => $level2) {

        $menu_tree .= '<li>
            <a href="#">'.$sm1.'<span class="fa arrow"></span></a>
            <ul class="nav collapse" style="background-color:#212d38">';

                foreach ($level2 as $sm2 => $level3) {

                if (count($level3) == 1 && empty($level3[0]['SUB_MENU3'])) {

                $url = base_url($level3[0]['CONTROLLER'] ?: 'home');

                $menu_tree .= '<li>
                    <a style="color:#b0cfc1;" href="'.$url.'">'.$sm2.'</a>
                </li>';

                } else {

                $menu_tree .= '<li>
                    <a href="#">'.$sm2.'<span class="fa arrow"></span></a>
                    <ul class="nav nav-fourth-level collapse" style="background-color:#1d262e">';

                        foreach ($level3 as $v3) {

                        $url = base_url($v3['CONTROLLER'] ?: 'home');
                        $label = $v3['SUB_MENU3'] ?: $sm2;

                        $menu_tree .= '<li>
                            <a style="color:#b0cfc1;" href="'.$url.'">'.$label.'</a>
                        </li>';
                        }

                        $menu_tree .= '</ul>
                </li>';
                }
                }

                $menu_tree .= '</ul>
        </li>';
        }

        $menu_tree .= '</ul>
</li>';
}

echo $menu_tree;
}