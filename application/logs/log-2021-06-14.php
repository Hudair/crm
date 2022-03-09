<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-06-14 13:52:04 --> Severity: Core Warning --> PHP Startup: Unable to load dynamic library '/usr/local/lib/php/pecl/20190902/imap.so' (tried: /usr/local/lib/php/pecl/20190902/imap.so (dlopen(/usr/local/lib/php/pecl/20190902/imap.so, 9): image not found), /usr/local/lib/php/pecl/20190902//usr/local/lib/php/pecl/20190902/imap.so.so (dlopen(/usr/local/lib/php/pecl/20190902//usr/local/lib/php/pecl/20190902/imap.so.so, 9): image not found)) Unknown 0
ERROR - 2021-06-14 08:22:12 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:12 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:12 --> Severity: Core Warning --> PHP Startup: Unable to load dynamic library '/usr/local/lib/php/pecl/20190902/imap.so' (tried: /usr/local/lib/php/pecl/20190902/imap.so (dlopen(/usr/local/lib/php/pecl/20190902/imap.so, 9): image not found), /usr/local/lib/php/pecl/20190902//usr/local/lib/php/pecl/20190902/imap.so.so (dlopen(/usr/local/lib/php/pecl/20190902//usr/local/lib/php/pecl/20190902/imap.so.so, 9): image not found)) Unknown 0
ERROR - 2021-06-14 08:22:16 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:16 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:19 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:19 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:22 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:22 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:22 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:27 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:27 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:27 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:29 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:29 --> 404 Page Not Found: /index
ERROR - 2021-06-14 08:22:29 --> 404 Page Not Found: /index
ERROR - 2021-06-14 16:21:38 --> Severity: Core Warning --> PHP Startup: Unable to load dynamic library '/usr/local/lib/php/pecl/20190902/imap.so' (tried: /usr/local/lib/php/pecl/20190902/imap.so (dlopen(/usr/local/lib/php/pecl/20190902/imap.so, 9): image not found), /usr/local/lib/php/pecl/20190902//usr/local/lib/php/pecl/20190902/imap.so.so (dlopen(/usr/local/lib/php/pecl/20190902//usr/local/lib/php/pecl/20190902/imap.so.so, 9): image not found)) Unknown 0
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'host' - Invalid query: ALTER TABLE `tbl_client` ADD  `host` VARCHAR(100) DEFAULT  ''
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_contact_id' - Invalid query: ALTER TABLE `tbl_client` ADD  `remote_contact_id` VARCHAR(224)  DEFAULT  NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'host' - Invalid query: ALTER TABLE `tbl_suppliers` ADD  `host` VARCHAR(100) DEFAULT  ''
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_contact_id' - Invalid query: ALTER TABLE `tbl_suppliers` ADD  `remote_contact_id`  VARCHAR(224)  DEFAULT  NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'host' - Invalid query: ALTER TABLE `tbl_invoices` ADD  `host` VARCHAR(100) DEFAULT  ''
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_invoice_id' - Invalid query: ALTER TABLE `tbl_invoices` ADD  `remote_invoice_id` VARCHAR(224)  DEFAULT  NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_client_id' - Invalid query: ALTER TABLE `tbl_invoices` ADD  `remote_client_id` VARCHAR(224)  DEFAULT  NULL
ERROR - 2021-06-14 20:44:41 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'IF EXISTS `reference_no`' at line 1 - Invalid query: ALTER TABLE `tbl_invoices` DROP INDEX IF EXISTS `reference_no`
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate key name 'remote_invoice_key' - Invalid query: ALTER TABLE `tbl_invoices` ADD UNIQUE  `remote_invoice_key` USING HASH(`host`, `remote_invoice_id`)
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'host' - Invalid query: ALTER TABLE `tbl_saved_items` ADD  `host` VARCHAR(100) DEFAULT ''
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_item_id' - Invalid query: ALTER TABLE `tbl_saved_items` ADD  `remote_item_id` VARCHAR(224)  DEFAULT  NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'host' - Invalid query: ALTER TABLE `tbl_items` ADD  `host` VARCHAR(100) DEFAULT ''
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_item_id' - Invalid query: ALTER TABLE `tbl_items` ADD  `remote_item_id` VARCHAR(224)  DEFAULT  NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_invoice_id' - Invalid query: ALTER TABLE `tbl_items` ADD  `remote_invoice_id` VARCHAR(224) DEFAULT NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'host' - Invalid query: ALTER TABLE `tbl_payments` ADD  `host` VARCHAR(100) DEFAULT ''
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_payment_id' - Invalid query: ALTER TABLE `tbl_payments` ADD  `remote_payment_id` VARCHAR(224)  DEFAULT  NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_invoice_id' - Invalid query: ALTER TABLE `tbl_payments` ADD  `remote_invoice_id` VARCHAR(224) DEFAULT NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_contact_id' - Invalid query: ALTER TABLE `tbl_payments` ADD  `remote_contact_id` VARCHAR(224) DEFAULT NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_account_id' - Invalid query: ALTER TABLE `tbl_payments` ADD  `remote_account_id` VARCHAR(224) DEFAULT NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate key name 'remote_payment_key' - Invalid query: ALTER TABLE `tbl_payments` ADD UNIQUE  `remote_payment_key`(`host`, `remote_payment_id`)
ERROR - 2021-06-14 20:44:41 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'IF EXISTS `reference_no`' at line 1 - Invalid query: ALTER TABLE `tbl_purchases` DROP INDEX IF EXISTS `reference_no`
ERROR - 2021-06-14 20:44:41 --> Query error: Key column 'remote_purchase_id' doesn't exist in table - Invalid query: ALTER TABLE `tbl_purchases` ADD UNIQUE  `remote_purchase_key` USING HASH(`host`, `remote_purchase_id`)
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'host' - Invalid query: ALTER TABLE `tbl_project` ADD  `host` VARCHAR(100) DEFAULT  ''
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_project_id' - Invalid query: ALTER TABLE `tbl_project` ADD  `remote_project_id` VARCHAR(224)  DEFAULT  NULL
ERROR - 2021-06-14 20:44:41 --> Query error: Duplicate column name 'remote_contact_id' - Invalid query: ALTER TABLE `tbl_project` ADD  `remote_contact_id` VARCHAR(224)  DEFAULT  NULL
ERROR - 2021-06-14 15:14:47 --> 404 Page Not Found: admin/Quickbook/index
ERROR - 2021-06-14 20:44:54 --> Severity: error --> Exception: 'ClientID' must be provided in OAuth2. /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/vendor/quickbooks/v3-php-sdk/src/Core/ServiceContext.php 205
ERROR - 2021-06-14 20:45:13 --> Severity: error --> Exception: 'ClientID' must be provided in OAuth2. /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/vendor/quickbooks/v3-php-sdk/src/Core/ServiceContext.php 205
ERROR - 2021-06-14 20:46:29 --> Severity: error --> Exception: 'ClientID' must be provided in OAuth2. /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/vendor/quickbooks/v3-php-sdk/src/Core/ServiceContext.php 205
ERROR - 2021-06-14 20:46:30 --> Severity: error --> Exception: 'ClientID' must be provided in OAuth2. /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/vendor/quickbooks/v3-php-sdk/src/Core/ServiceContext.php 205
ERROR - 2021-06-14 20:46:31 --> Severity: error --> Exception: 'ClientID' must be provided in OAuth2. /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/vendor/quickbooks/v3-php-sdk/src/Core/ServiceContext.php 205
ERROR - 2021-06-14 20:46:31 --> Severity: error --> Exception: 'ClientID' must be provided in OAuth2. /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/vendor/quickbooks/v3-php-sdk/src/Core/ServiceContext.php 205
ERROR - 2021-06-14 20:46:31 --> Severity: error --> Exception: 'ClientID' must be provided in OAuth2. /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/vendor/quickbooks/v3-php-sdk/src/Core/ServiceContext.php 205
ERROR - 2021-06-14 20:47:07 --> Severity: error --> Exception: Call to a member function getOAuth2LoginHelper() on null /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/controllers/Quickbooks.php 68
ERROR - 2021-06-14 20:47:08 --> Severity: error --> Exception: Call to a member function getOAuth2LoginHelper() on null /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/controllers/Quickbooks.php 68
ERROR - 2021-06-14 20:47:08 --> Severity: error --> Exception: Call to a member function getOAuth2LoginHelper() on null /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/controllers/Quickbooks.php 68
ERROR - 2021-06-14 20:47:08 --> Severity: error --> Exception: Call to a member function getOAuth2LoginHelper() on null /Library/WebServer/Documents/UPM_Minified/modules/quickbooks/controllers/Quickbooks.php 68
ERROR - 2021-06-14 20:47:55 --> Severity: Notice --> Undefined variable: authUrl /Library/WebServer/Documents/UPM_Minified/application/third_party/MX/Loader.php(331) : eval()'d code 113
