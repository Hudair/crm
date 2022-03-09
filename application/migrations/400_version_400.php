<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_400 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("DROP TABLE IF EXISTS `tbl_online_payment`;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_online_payment` (
            `online_payment_id` int NOT NULL AUTO_INCREMENT,
            `gateway_name` varchar(20) NOT NULL,
            `icon` text NOT NULL,
            `field_1` varchar(100) DEFAULT NULL,
            `field_2` varchar(100) DEFAULT NULL,
            `field_3` varchar(100) DEFAULT NULL,
            `field_4` varchar(100) DEFAULT NULL,
            `field_5` varchar(100) DEFAULT NULL,
            `link` varchar(100) DEFAULT NULL,
            `modal` enum('Yes','No') DEFAULT NULL,
            PRIMARY KEY (`online_payment_id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;");

        $this->db->query("INSERT INTO `tbl_online_payment` (`online_payment_id`, `gateway_name`, `icon`, `field_1`, `field_2`, `field_3`, `field_4`, `field_5`, `link`, `modal`) VALUES
        (1, 'paypal', 'asset/images/payment_logo/paypal.png', 'paypal_api_username', 'paypal_api_password|password', 'api_signature', 'paypal_live|checkbox', '', 'payment/paypal', 'Yes'),
        (2, 'Stripe', 'asset/images/payment_logo/stripe.jpg', 'stripe_private_key', 'stripe_public_key', NULL, NULL, NULL, 'payment/stripe', 'Yes'),
        (3, '2checkout', 'asset/images/payment_logo/2checkout.jpg', '2checkout_live|checkbox', '2checkout_publishable_key', '2checkout_private_key', '2checkout_seller_id', NULL, 'payment/checkout', 'No'),
        (4, 'Authorize.net', 'asset/images/payment_logo/Authorizenet.png', 'aim_api_login_id', 'aim_authorize_transaction_key', 'aim_authorize_live|checkbox', '', '', 'payment/authorize', 'No'),
        (5, 'CCAvenue', 'asset/images/payment_logo/CCAvenue.jpg', 'ccavenue_merchant_id', 'ccavenue_key', 'ccavenue_access_code', 'ccavenue_enable_test_mode|checkbox', '', 'payment/ccavenue', 'No'),
        (6, 'Braintree', 'asset/images/payment_logo/Braintree.png', 'braintree_merchant_id', 'braintree_private_key', 'braintree_public_key', 'braintree_live_or_sandbox|checkbox', '', 'payment/braintree', 'No'),
        (7, 'Mollie', 'asset/images/payment_logo/ideal_mollie.png', 'mollie_api_key', 'mollie_partner_id', NULL, NULL, NULL, 'payment/mollie', 'Yes'),
        (8, 'PayUmoney', 'asset/images/payment_logo/payumoney.jpg', 'payumoney_key', 'payumoney_salt', 'payumoney_enable_test_mode|checkbox', '', NULL, 'payment/payumoney', 'No'),
        (9, 'Razorpay', 'asset/images/payment_logo/razorpay.png', 'razorpay_key', NULL, NULL, NULL, NULL, 'payment/razorpay', 'Yes'),
        (10, 'TapPayment', 'asset/images/payment_logo/tappayment.jpg', 'tap_api_key', 'tap_user_name', 'tap_password|password', 'tap_merchantID', '', 'payment/TapPayment', 'Yes');");

        $this->db->query("ALTER TABLE `tbl_invoices` CHANGE `allow_authorize` `allow_authorize_net` ENUM('Yes','No') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No';");
        $this->db->query("ALTER TABLE `tbl_invoices` CHANGE `allow_tapPayment` `allow_tappayment` ENUM('Yes','No') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'Yes';");
        $this->db->query("UPDATE `tbl_config` SET `config_key` = 'authorize_net_status' WHERE `tbl_config`.`config_key` = 'authorize_status';");

        $this->db->query("INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `status`) VALUES (NULL, 'sms_settings', 'admin/settings/sms_settings', 'fa fa-fw fa-envelope', '25', '0', '2');");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '1' WHERE `tbl_menu`.`menu_id` = 111;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '2' WHERE `tbl_menu`.`menu_id` = 112;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '3' WHERE `tbl_menu`.`menu_id` = 113;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '4' WHERE `tbl_menu`.`menu_id` = 163;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '5' WHERE `tbl_menu`.`menu_id` = 114;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '6' WHERE `tbl_menu`.`menu_id` = 115;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '7' WHERE `tbl_menu`.`menu_id` = 116;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '8' WHERE `tbl_menu`.`menu_id` = 117;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '9' WHERE `tbl_menu`.`menu_id` = 157;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '10' WHERE `tbl_menu`.`menu_id` = 118;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '11' WHERE `tbl_menu`.`menu_id` = 158;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '12' WHERE `tbl_menu`.`menu_id` = 155;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '12' WHERE `tbl_menu`.`menu_id` = 155;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '13' WHERE `tbl_menu`.`menu_id` = 159;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '14' WHERE `tbl_menu`.`menu_id` = 119;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '15' WHERE `tbl_menu`.`menu_id` = 162;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '16' WHERE `tbl_menu`.`menu_id` = 127;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '17' WHERE `tbl_menu`.`menu_id` = 128;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '18' WHERE `tbl_menu`.`menu_id` = 161;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '19' WHERE `tbl_menu`.`menu_id` = 129;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '20' WHERE `tbl_menu`.`menu_id` = 120;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '21' WHERE `tbl_menu`.`menu_id` = 145;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '22' WHERE `tbl_menu`.`menu_id` = 121;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '23' WHERE `tbl_menu`.`menu_id` = 122;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '24' WHERE `tbl_menu`.`menu_id` = 123;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '25' WHERE `tbl_menu`.`menu_id` = 124;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '26' WHERE `tbl_menu`.`menu_id` = 125;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '27' WHERE `tbl_menu`.`menu_id` = 149;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '28' WHERE `tbl_menu`.`menu_id` = 130;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '29' WHERE `tbl_menu`.`menu_id` = 131;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '30' WHERE `tbl_menu`.`menu_id` = 160;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '31' WHERE `tbl_menu`.`menu_id` = 132;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '32' WHERE `tbl_menu`.`menu_id` = 133;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '33' WHERE `tbl_menu`.`menu_id` = 134;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '34' WHERE `tbl_menu`.`menu_id` = 135;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '35' WHERE `tbl_menu`.`menu_id` = 136;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '36' WHERE `tbl_menu`.`menu_id` = 137;");
        $this->db->query("UPDATE `tbl_menu` SET `sort` = '37' WHERE `tbl_menu`.`menu_id` = 138;");
        $this->db->query("DELETE FROM `tbl_menu` WHERE `tbl_menu`.`menu_id` = 126");
        $this->db->query("UPDATE `tbl_config` SET `value` = '4.0.0' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
