<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_304 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("ALTER TABLE `tbl_quotations` ADD `is_convert` ENUM('Yes','No') NOT NULL DEFAULT 'No' AFTER `quotations_status`, ADD `convert_module` VARCHAR(20) NULL DEFAULT NULL AFTER `is_convert`, ADD `convert_module_id` INT(11) NULL DEFAULT NULL AFTER `convert_module`;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_modules` (
            `module_id` int NOT NULL AUTO_INCREMENT,
            `module_name` varchar(55) NOT NULL,
            `installed_version` varchar(11) NOT NULL,
            `active` tinyint(1) NOT NULL,
            PRIMARY KEY (`module_id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
        $this->db->query("UPDATE `tbl_config` SET `value` = '3.0.4' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
