-- Adminer 4.7.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `ipchanger` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ipchanger`;

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE `configurations` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(255) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `setting_key` (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `configurations` (`config_id`, `config_key`, `config_value`, `status`, `date_created`, `date_updated`) VALUES
(1,	'site_name',	'Static Maker',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(2,	'site_url',	'http://127.0.0.1/StaticMaker/public/',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(3,	'site_port',	'80',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(4,	'cloudflare_email',	'email@example.com',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(5,	'cloudflare_api_key',	'xxxxx',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(6,	'cloudflare_zone_id',	'xxxxx',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(7,	'notification_send_email',	'0',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(8,	'notification_host',	'mail.example.com',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(9,	'notification_from_email',	'sender@example.com',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(10, 'notification_from_password',	'xxxxx',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(11, 'notification_from_name',	'Cron Mailer',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(12, 'notification_to_email',	'receiver@example.com',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(13, 'notification_to_name',	'Receiver',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(14, 'notification_port',	'587',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(15, 'cron_ipchecker',	'0',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(16, 'cron_frequency_ipchecker',	'15',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(17, 'cron_speedtest',	'0',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(18, 'cron_frequency_speedtest',	'15',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30'),
(19, 'default_timezone',	'UTC',	1,	'2020-01-27 22:37:45',	'2020-02-05 23:11:30');

DROP TABLE IF EXISTS `dns_records`;
CREATE TABLE `dns_records` (
  `dns_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_type` varchar(20) NOT NULL,
  `record_name` varchar(255) NOT NULL,
  `record_value` varchar(255) NOT NULL,
  `is_proxied` tinyint(4) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`dns_record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ips`;
CREATE TABLE `ips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `type` enum('IPv4','IPv6') DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `speed_tests`;
CREATE TABLE `speed_tests` (
  `speed_test_id` int(11) NOT NULL AUTO_INCREMENT,
  `download` float NOT NULL,
  `upload` float NOT NULL,
  `latency` float NOT NULL,
  `response_data` text NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`speed_test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2020-02-05 23:27:58
