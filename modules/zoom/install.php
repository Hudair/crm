<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->db->query(
  "INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`) 
  VALUES (NULL, 'zoom', 'admin/zoom', 'fa fa-file-video-o', '0', '3', CURRENT_TIMESTAMP, '1');"
);

$CI->db->query(
  "CREATE TABLE IF NOT EXISTS `tbl_zoom_meeting` (
    `zoom_meeting_id` int NOT NULL AUTO_INCREMENT,
    `user_id` text,
    `client_id` text,
    `leads_id` varchar(100) DEFAULT NULL,
    `host` int DEFAULT NULL,
    `meetingId` varchar(500) DEFAULT NULL,
    `start_url` text,
    `join_url` varchar(500) DEFAULT NULL,
    `topic` varchar(250) DEFAULT NULL,
    `duration` varchar(100) DEFAULT NULL,
    `meeting_time` datetime DEFAULT NULL,
    `meeting_start` varchar(40) DEFAULT NULL,
    `notes` text,
    `additional` varchar(500) DEFAULT NULL,
    `status` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`zoom_meeting_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8;"
);

$CI->db->query(
  "INSERT INTO `tbl_email_templates` (`email_templates_id`, `code`, `email_group`, `subject`, `template_body`) VALUES (NULL, 'en', 'meeting_start', 'Your Meeting start now.we are waiting', ' <p>Hello {USER}!</p>\r\n\r\n\r\n<p>A Meeting start from {HOST}.you are invited.</p>\r\n\r\n\r\n<p>we are waiting for you:<br>\r\n</p><p> Click on link to join the meeting:<br>\r\n<big><strong><a href=\"{MEETING_URL}\">join the meeting...</a></strong></big><br>\r\nLink doesn\'t work? Copy the following link to your browser address bar:<br>\r\n<a href=\"{MEETING_URL}\">{MEETING_URL}</a></p>\r\n\r\n\r\n<p><br>\r\n<br>\r\nHave fun!<br>\r\nThe {SITE_NAME} Team</p>');"
);
