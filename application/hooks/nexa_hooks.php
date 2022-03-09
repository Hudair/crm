<?php 
function get_config_items(){
$CI =& get_instance();
$session = $CI->load->library('session');
//$db = $CI->load->library('database');
print_r($session); exit;
$config_items = $db->query("SELECT * FROM tbl_config")->result_array();

return $config_items;
}

define("CONFIG_ITEMS", get_config_items());