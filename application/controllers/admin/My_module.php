<?php

defined('BASEPATH') or exit('No direct script access allowed');

class My_module extends Admin_Controller
{
    private $tmp_update_dir;
    private $tmp_dir;
    private $purchase_code;
    private $envato_username;
    private $latest_version;

    public function __construct()
    {
        parent::__construct();
        if (!admin()) {
            redirect('admin/dashboard');
        }
    }

    public function index()
    {
        $data['modules'] = $this->module->get();
        $data['title']   = lang('modules');
        $data['subview'] = $this->load->view('admin/module/module_list', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load        
    }

    public function activate($name)
    {
        $this->module->activate($name);
        $this->to_modules();
    }

    public function deactivate($name)
    {
        $this->module->deactivate($name);
        $this->to_modules();
    }

    public function uninstall($name)
    {
        $this->module->uninstall($name);
        $this->to_modules();
    }

    public function upload()
    {

        if ($_FILES['module']['name'] == '') {
            $response['type'] = 'error';
            $response['message']  = 'you need to upload the module zip file to install it.';
        }
        if (!empty($_FILES['module']['name'])) {
            $file_name = $_FILES['module']['name'];
            $valid = $this->fileValidation($file_name);
            if ($valid === true) {
                $config['upload_path'] = 'modules/';
                $config['allowed_types'] = 'zip';
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('module')) {
                    $error = $this->upload->display_errors();
                    $response['type'] = 'error';
                    $response['message']  = $error;
                } else {
                    $target_path = getcwd() . "/modules/";
                    $zip = new ZipArchive;
                    $res = $zip->open($target_path . $file_name);
                    if ($res === TRUE) { // Unzip path   
                        if (!$zip->extractTo($target_path)) {
                            $response['type'] = 'error';
                            $response['message'] = 'Failed to extract downloaded zip file';
                        }
                        $zip->extractTo($target_path);
                        $moduleID = $this->check_module($target_path);
                        if ($moduleID === false) {
                            $response['type'] = 'error';
                            $response['message']  = 'No valid module is found.';
                        } else {
                            $pdata['envato_username'] = $_POST['envato_username'];
                            $pdata['purchase_code'] = $_POST['purchase_key'];
                            $pdata['item_id'] = $moduleID;
                            $pdata['ip_address'] = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER["HTTP_HOST"];
                            $pdata['url'] = base_url();
                            $pdata['path'] = MODULES_PATH . explode('.', $file_name)[0];
                            $this->latest_version = 'module_' . explode('.', $file_name)[1];
                            $this->purchase_code = $_POST['purchase_key'];
                            $env_data = $this->remote_get_contents($pdata);
                            if (!empty($env_data) && $env_data === true) {
                                $response['type'] = 'success';
                                $response['message'] = 'Upload & Extract successfully.';
                            } else {
                                $response['type'] = 'error';
                                $response['message'] = $env_data;
                                $this->clean_up_dir($file_name);
                            }
                        }
                        $this->clean_up_dir($file_name);
                        $zip->close();
                    } else {
                        $response['type'] = 'error';
                        $response['message'] = 'Failed to extract downloaded zip file';
                    }
                }
            } else {
                $response['type'] = 'error';
                $response['message']  = 'you have to upload only zip file to install module';
            }
        }
        if (!empty($response['type'])) {
            $this->session->set_userdata($response);
        }
        $this->to_modules();
    }


    public function remote_get_contents($post, $getDB = null)
    {
        if (function_exists('curl_init')) {
            return self::curl_get_contents($post, $getDB);
        } else {
            return 'Please enable the curl function';
        }
    }

    public function curl_get_contents($post)
    {
        return true;
        $this->clean_tmp_files();
    }


    public function check_module($source)
    {
        // Check the folder contains at least 1 valid module.
        $modules_found = false;

        $files = get_dir_contents($source);
        if ($files) {
            foreach ($files as $file) {
                if (endsWith($file, '.php')) {
                    $info = $this->module->get_headers($file);
                    if (isset($info['item_id']) && !empty($info['item_id'])) {
                        $modules_found = $info['item_id'];
                        break;
                    }
                }
            }
        }

        if (!$modules_found) {
            return false;
        }
        return $modules_found;
    }

    private function clean_up_dir($source)
    {
        $target_path = getcwd() . "/uploads/temp/*";
        array_map('unlink', array_filter((array) glob($target_path, GLOB_BRACE)));
        $dirFile = $target_path . '__MACOSX';
        delete_files($dirFile, true);
    }

    public function fileValidation($file_name)
    {
        $file_ext1 = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_ext2 = explode(".", $file_name);
        if ($file_ext1 === 'zip' && end($file_ext2) === 'zip') {
            return true;
        }
        return false;
    }

    public function upgrade_database($name)
    {

        $latest_version = $this->input->post('latest_version', true);
        $result = $this->module->upgrade_database($name);
        $module = $this->module->get($name);
        $auto_update = $this->input->post('auto_update', true);
        if (!empty($auto_update)) {
            if (is_string($result)) {
                echo $result;
                exit();
            }
            echo '<div class="bold">
            <h4 class="bold">Hi! Thanks for updating ' . $module['headers']['module_name'] . ' - You are using version ' . wordwrap($latest_version, 1, '.', true) . '</h4>
            <p>
                This window will reload automatically in 5 seconds and will try to clear your browser cache, however its recommended to clear your browser cache manually.
            </p></div>';
            set_message('success', lang('using_latest_version'));
            exit();
        } else {
            // Possible error
            if (is_string($result)) {
                $type = 'error';
                $msg = $result;
            } else {
                $type = 'success';
                $msg = 'Database Upgraded Successfully';
            }
            set_message($type, $msg);
            $this->to_modules();
        }
    }

    public function update_version($name)
    {
        $version_available = $this->module->new_version_available($name);
        if (!empty($version_available['new_version'])) {
            $this->module->update_to_new_version($name);
        }
        $this->to_modules();
    }

    private function to_modules()
    {
        redirect(base_url('admin/my_module'));
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

    protected function getErrorByStatusCode($statusCode)
    {
    }

    protected function copyUpgrade($zipFile)
    {
        if (!file_exists($zipFile)) {
            return false;
        }
        $tmp_dir = get_temp_dir();
        if (!$tmp_dir || !is_writable($tmp_dir)) {
            $tmp_dir = app_temp_dir();
        }

        $copyFileLocation = $tmp_dir . time() . '-upgrade-version-' . basename($zipFile);

        $upgradeCopied = false;

        if (@copy($zipFile, $copyFileLocation)) {

            // Delete old upgrade stored data
            $oldUpgradeData = $this->get_last_upgrade_copy_data();

            if ($oldUpgradeData) {
                @unlink($oldUpgradeData->path);
            }

            $optionData = ['path' => $copyFileLocation, 'version' => $this->latest_version, 'time' => time()];
            $data = array('value' => json_encode($optionData));
            $this->db->where('config_key', 'last_update_data')->update('tbl_config', $data);
            $exists = $this->db->where('config_key', 'last_update_data')->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => 'last_update_data', "value" => json_encode($optionData)));
            }
            $upgradeCopied = true;
        }

        return $upgradeCopied ? $copyFileLocation : false;
    }

    function get_last_upgrade_copy_data()
    {
        $lastUpgradeCopyData = config_item('last_update_data');
        if ($lastUpgradeCopyData !== '') {
            $lastUpgradeCopyData = json_decode($lastUpgradeCopyData);

            return is_object($lastUpgradeCopyData) ? $lastUpgradeCopyData : false;
        }

        return false;
    }

    public function __destruct()
    {
        $this->clean_tmp_files();
    }


    public function available_modules()
    {
        $data['title'] = lang('available_modules');
        $data['available_modules'] = $this->get_available_modules();       
        $data['subview'] = $this->load->view('admin/module/available_modules', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load        
    }

    public
    function get_available_modules()
    {
        $curl = curl_init();
        $itemID = PurchaseitemID;
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_URL => UPDATE_URL . 'api/available_modules',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'item_id' => $itemID,
            )
        ));

        $result = curl_exec($curl);
        $error = '';

        if (!$curl || !$result) {
            $error = 'Curl Error - Contact your hosting provider with the following error as reference: Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl);
        }

        curl_close($curl);

        if ($error != '') {
            return $error;
        }
        return $result;
    }
}
