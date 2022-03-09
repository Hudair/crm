<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_317 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("UPDATE `tbl_config` SET `value` = '3.1.7' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
