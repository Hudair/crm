<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->db->query("DELETE FROM `tbl_menu` WHERE `tbl_menu`.`label` = 'zoom'");
$CI->db->query("UPDATE `tbl_config` SET `value` = NULL WHERE `tbl_config`.`config_key` = 'zoom_api_key';");

