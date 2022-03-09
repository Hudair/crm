<?php defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Zoom meeting for ZiscorERP
Module ID: 32081244
Module uri: https://codecanyon.net/item/zoom-meeting-for-ziscoerp/32081244
Description: Zoom meeting Management for ZiscoERP
Version: 1.0.1
Author: unique_coder
Author uri: https://codecanyon.net/user/unique_coder
Requires at least: 4.0.2
*/
define('ZOOM_MODULE', 'zoom');
/**
 * Load the module helper
 */
$CI = &get_instance();

/**
 * Register language files, must be registered if the module is using languages
 */
module_languagesFiles(ZOOM_MODULE, [ZOOM_MODULE]);

$CI->load->helper(ZOOM_MODULE . '/zoom');
