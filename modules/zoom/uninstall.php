<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->db->query("DELETE FROM `tbl_menu` WHERE `tbl_menu`.`label` = 'zoom'");
$CI->db->query("DELETE FROM `tbl_email_templates` WHERE `tbl_email_templates`.`email_group` = 'meeting_start'");
$CI->db->query("UPDATE `tbl_config` SET `value` = NULL WHERE `tbl_config`.`config_key` = 'zoom_api_key';");
$CI->db->query("UPDATE `tbl_config` SET `value` = NULL WHERE `tbl_config`.`config_key` = 'zoom_secret_key';");
$CI->db->query("DROP TABLE `tbl_zoom_meeting`");