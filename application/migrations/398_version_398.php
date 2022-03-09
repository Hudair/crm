<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_398 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("UPDATE `tbl_config` SET `value` = '3.0.8' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
