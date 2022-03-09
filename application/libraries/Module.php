<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Module
{

    private static $supports = [];

    private $ci;

    private $db_modules = [];

    private $modules = [];

    private $active_modules = [];

    private $version = [];
    private $new_version_data = [];

    public $css = [];
    public $js = [];

    public function __construct()
    {
        $this->ci = &get_instance();

        $this->ci->db->query("CREATE TABLE IF NOT EXISTS `tbl_modules` (
            `module_id` int NOT NULL AUTO_INCREMENT,
            `module_name` varchar(55) NOT NULL,
            `installed_version` varchar(11) NOT NULL,
            `active` tinyint(1) NOT NULL,
            PRIMARY KEY (`module_id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

        $this->ci->load->model('admin_model');
        $this->ci->load->helper('directory');
        if ($this->ci->admin_model->get_current_db_version() < 400) {
            return;
        }
        $this->initialize();
    }

    public function activate($name)
    {
        $module = $this->get($name);

        if (!$module) {
            return false;
        }

        if (!$this->module_exists_in_database($name)) {
            $this->ci->db->where('module_name', $name);
            $this->ci->db->insert('tbl_modules', ['module_name' => $name, 'installed_version' => $module['headers']['version']]);
        }
        include_once($module['init_file']);

        $instalation = $module['path'] . 'install.php';
        if (file_exists($instalation)) {
            include_once($instalation);
        }

        $this->ci->db->where('module_name', $name);
        $this->ci->db->update('tbl_modules', ['active' => 1]);

        return true;
    }

    /**
     * Deactivate Module
     * @param string $name Module Name [system_name]
     * @return boolean
     */

    public function deactivate($name)
    {
        $module = $this->get($name);
        if (!$module) {
            return false;
        }

        $uninstallPath = $module['path'] . 'deactive.php';
        if (file_exists($uninstallPath)) {
            include_once($uninstallPath);
        }

        $this->ci->db->where('module_name', $name);
        $this->ci->db->update('tbl_modules', ['active' => 0]);
        return true;
    }

    public function uninstall($name)
    {
        $module = $this->get($name);
        if (!$module) {
            return false;
        }
        if ($module['activated'] == 1) {
            return false;
        }
        $this->ci->db->where('module_name', $name);
        $this->ci->db->delete('tbl_modules');

        $uninstallPath = $module['path'] . 'uninstall.php';
        if (file_exists($uninstallPath)) {
            include_once($uninstallPath);
        }

        if (is_dir($module['path'])) {
            delete_files($module['path'], true);
            rmdir($module['path']);
        }
        return true;
    }

    public function get_active_module()
    {
        return $this->active_modules;
    }

    public function is_active($name)
    {
        return array_key_exists($name, $this->get_active_module());
    }

    public function is_inactive($name)
    {
        return !$this->is_active($name);
    }

    public function is_installed($name)
    {
        if (!isset($this->modules[$name])) {
            return false;
        }
        return $this->modules[$name]['installed_version'] !== false;
    }

    public function is_minimum_version_requirement_met($name)
    {
        $module = $this->get($name);

        if (!isset($module['headers']['requires_at_least'])) {
            return true;
        }
        $appVersion = wordwrap(config_item('migration_version'), 1, '.', true);
        $moduleRequiresAppVersion = $module['headers']['requires_at_least'];
        if (version_compare($appVersion, $moduleRequiresAppVersion, '>=')) {
            return true;
        }

        return false;
    }

    public function upgrade_database($name)
    {
        $migration = new Module_Migration($name);
        if ($migration->to_latest() === false) {
            return $migration->error_string();
        }
        return true;
    }


    public function is_database_upgrade_required($name)
    {
        $module = $this->get($name);
        $moduleInstalledVersion = $module['installed_version'];
        if ($moduleInstalledVersion == false) {
            return false;
        }
        $moduleFilesVersion = $module['headers']['version'];
        if (version_compare($moduleInstalledVersion, $moduleFilesVersion) === 1) {
            return true;
        }

        if (version_compare($moduleFilesVersion, $moduleInstalledVersion) === 1) {
            return true;
        }
        return false;
    }

    public function latest_version($name)
    {
        $module = $this->get($name);
        if (!empty($module['headers']['item_id'])) {
            $module['item_id'] = $module['headers']['item_id'];
            $result = $this->ci->admin_model->get_update_info($module);
            $result = json_decode($result);
            return $result->latest_version;
        }
        return 0;
    }

    public function new_version_available($name)
    {
        $module = $this->get($name);
        $rdata = array();
        if (!empty($module['headers']['item_id'])) {
            $module['item_id'] = $module['headers']['item_id'];
            if (!empty($module['installed_version'])) {
                $version = str_replace(".", "", $module['installed_version']);
                $result = $this->ci->admin_model->get_update_info($module);
                $result = json_decode($result);
                if (!empty($result)) {
                    $latest_version = $result->latest_version;
                    if ($latest_version > $version) {
                        $rdata['new_version'] = $result->latest_version;
                    }
                }
                if (!empty($result->changelog)) {
                    $rdata['changelog'] = $result->changelog;
                    $rdata['changelog_url'] = $result->changelog_url;
                }
            }
        }
        return $rdata;
    }


    public function update_to_new_version($name)
    {
        $module = $this->get($name);
        $version_available = $this->auto_update->index($name);
        echo '<pre>';
        print_r($version_available);
        exit();
        if (!empty($version_available['new_version'])) {
        }


        // return $this->get_new_version_data($name);
    }




    /**
     * Return the number of modules that requires database upgrade
     * @return integer
     */
    public function number_of_modules_that_require_database_upgrade()
    {
        $CI = &get_instance();
        $cacheKey = 'no-of-modules-require-database-upgrade';
        $total = $CI->app_object_cache->get($cacheKey);
        if ($total === false) {
            $total = 0;
            foreach ($this->modules as $module) {
                if ($this->is_database_upgrade_required($module['system_name'])) {
                    $total++;
                }
            }
            $CI->app_object_cache->add($cacheKey, $total);
        }

        return $total;
    }

    /**
     * Get all modules or specific module if module system name is passed
     * This method returns all modules including active and inactive
     * @param mixed $module
     * @return mixed
     */
    public function get($module = null)
    {
        if (!$module) {
            $modules = $this->modules;
            /* Sort modules by name */

            usort($modules, function ($a, $b) {
                return strcmp(strtolower($a['headers']['module_name']), strtolower($b['headers']['module_name']));
            });

            return $modules;
        }

        if (isset($this->modules[$module])) {
            return $this->modules[$module];
        }

        return null;
    }

    public function get_module_from_database($name)
    {
        if (isset($this->db_modules[$name])) {
            return $this->db_modules[$name];
        }
        return $this->get_module_info($name);
    }

    public function initialize()
    {
        // For caching
        $this->query_db_modules();
        foreach ($this->get_valid_modules() as $module) {
            $name = $module['name'];
            if (!isset($this->modules[$name])) {
                $this->modules[$name]['system_name'] = $name;
                $this->modules[$name]['headers'] = $this->get_headers($module['init_file']);
                $this->modules[$name]['init_file'] = $module['init_file'];
                $this->modules[$name]['path'] = $module['path'];

                $get_module = $this->get_module_from_database($name);
                $this->modules[$name]['activated'] = 0;
                if (!empty($get_module)) {
                    if ($get_module->active == 1) {
                        $this->modules[$name]['activated'] = 1;
                        $this->active_modules[$name] = $this->modules[$name];
                    }
                }
                $this->modules[$name]['installed_version'] = $get_module ? $get_module->installed_version : false;
            }
        }
    }

    public function add_supports_feature($module_name, $feature)
    {
        if (!isset(self::$supports[$module_name])) {
            self::$supports[$module_name] = [];
        }

        if (in_array($feature, self::$supports[$module_name])) {
            return;
        }

        self::$supports[$module_name][] = $feature;
    }

    public function supports_feature($module_name, $feature)
    {
        return isset(self::$supports[$module_name]) && in_array($feature, self::$supports[$module_name]);
    }

    /**
     * Get module headers info
     * @param string $module_source the module init file location
     * @return array
     */
    public function get_headers($module_source)
    {
        $module_data = read_file($module_source); // Read the module init file.

        preg_match('|Module Name:(.*)$|mi', $module_data, $name);
        preg_match('|Module ID:(.*)$|mi', $module_data, $item_id);
        // preg_match('|Item ID:(.*)$|mi', $module_data, $items_id);
        preg_match('|Module URI:(.*)$|mi', $module_data, $uri);
        preg_match('|Version:(.*)|i', $module_data, $version);
        preg_match('|Description:(.*)$|mi', $module_data, $description);
        preg_match('|Author:(.*)$|mi', $module_data, $author_name);
        preg_match('|Author URI:(.*)$|mi', $module_data, $author_uri);
        preg_match('|Requires at least:(.*)$|mi', $module_data, $requires_at_least);

        $arr = [];

        if (isset($name[1])) {
            $arr['module_name'] = trim($name[1]);
        }
        if (isset($item_id[1])) {
            $arr['item_id'] = trim($item_id[1]);
        }
        // if (isset($items_id[1])) {
        //     $arr['item_id'] = trim($items_id[1]);
        // }
        if (isset($uri[1])) {
            $arr['uri'] = trim($uri[1]);
        }

        if (isset($version[1])) {
            $arr['version'] = trim($version[1]);
        } else {
            $arr['version'] = 0;
        }

        if (isset($description[1])) {
            $arr['description'] = trim($description[1]);
        }

        if (isset($author_name[1])) {
            $arr['author'] = trim($author_name[1]);
        }

        if (isset($author_uri[1])) {
            $arr['author_uri'] = trim($author_uri[1]);
        }

        if (isset($requires_at_least[1])) {
            $arr['requires_at_least'] = trim($requires_at_least[1]);
        }

        return $arr;
    }

    /**
     * Check whether module is inserted into database table
     * @param string $name module system name
     * @return boolean
     */
    private function module_exists_in_database($name)
    {
        return (bool)$this->get_module_from_database($name);
    }

    /**
     * Get valid modules
     * @return array
     */
    private function get_valid_modules()
    {
        $modules = directory_map(MODULES_PATH, 1);

        $valid_modules = [];

        if ($modules) {
            foreach ($modules as $name) {
                $name = strtolower(trim($name));

                /**
                 * Filename may be returned like chat/ or chat\ from the directory_map function
                 */
                foreach (['\\', '/'] as $trim) {
                    $name = rtrim($name, $trim);
                }

                // If the module hasn't already been added and isn't a file
                if (!stripos($name, '.')) {
                    $module_path = MODULES_PATH . $name . '/';
                    $init_file = $module_path . $name . '.php';
                    // Make sure a valid module file by the same name as the folder exists
                    if (file_exists($init_file)) {
                        $valid_modules[] = [
                            'init_file' => $init_file,
                            'name' => $name,
                            'path' => $module_path,
                        ];
                    }
                }
            }
        }

        return $valid_modules;
    }

    private function query_db_modules()
    {
        $db_modules = get_result('tbl_modules');
        foreach ($db_modules as $db_module) {
            $this->db_modules[$db_module->module_name] = $db_module;
        }
    }
    private function get_module_info($name)
    {
        return  get_row('tbl_modules', array('module_name' => $name));
    }

    private
    function clean_tmp_files()
    {
        if (is_dir($this->tmp_update_dir)) {
            @delete_files($this->tmp_update_dir);
            if (@!delete_dir($this->tmp_update_dir)) {
                @rename($this->tmp_update_dir, $this->tmp_dir . 'delete_this_' . uniqid());
            }
        }
    }
    public function load_css()
    {

        $all_css = $this->css;
        $css = '';
        if (!empty($all_css)) {
            foreach ($all_css as $vcss) {
                $css .= '<link href="' . base_url($vcss) . '"  rel="stylesheet" type="text/css" >';
            }
        }
        return $css;
    }

    public function load_js()
    {
        $all_js = $this->js;
        $js = '';
        if (!empty($all_js)) {
            foreach ($all_js as $vjs) {
                $js .= '<script src="' . base_url($vjs) . '"></script>';
            }
        }
        return $js;
    }
}
