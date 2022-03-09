<?php defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('can_action_by_label')) {
    function can_action_by_label($label, $action)
    {
        $CI = &get_instance();
        $menu_id = get_any_field('tbl_menu', array('label' => $label), 'menu_id');
        $designations_id = $CI->session->userdata('designations_id');
        $where = array('designations_id' => $designations_id, $action => $menu_id);
        $user_type = $CI->session->userdata('user_type');
        if ($user_type == 1) {
            return true;
        } else {
            $can_do = $CI->db->where($where)->get('tbl_user_role')->row();
            if (!empty($can_do)) {
                return true;
            }
        }
    }
}
