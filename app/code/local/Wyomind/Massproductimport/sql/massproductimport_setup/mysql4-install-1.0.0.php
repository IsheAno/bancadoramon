<?php

$installer = $this;
$installer->startSetup();
$installer->run("
 DROP TABLE IF EXISTS {$this->getTable('massproductimport_profile')};
 CREATE TABLE {$this->getTable('massproductimport_profile')} (
  `profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(100) DEFAULT NULL,
  `file_path` varchar(250) DEFAULT NULL,
  `file_separator` varchar(4) DEFAULT ';',
  `file_enclosure` varchar(4) DEFAULT ';',
  `auto_set_instock` int(1) DEFAULT '1',
  `mapping` text,
  `cron_setting` text,
  `imported_at` datetime DEFAULT NULL,
  `sku_offset` int(2) DEFAULT '0',
  `use_custom_rules` int(1) DEFAULT '0',
  `custom_rules` text,
  `identifier_code` text,
  `file_system_type` int(1) DEFAULT '0',
  `use_sftp` int(1) DEFAULT '0',
  `ftp_host` varchar(300) DEFAULT NULL,
  `ftp_login` varchar(300) DEFAULT NULL,
  `ftp_password` varchar(300) DEFAULT NULL,
  `ftp_active` int(1) DEFAULT '0',
  `ftp_dir` varchar(300) DEFAULT NULL,
  `file_type` int(1) DEFAULT '0',
  `xpath_to_product` varchar(400) DEFAULT NULL,
  `default_values` text,
  `category_mapping` text,
  `images_system_type` int(1) DEFAULT 2,
  `images_use_sftp` int(1) DEFAULT 0,
  `images_ftp_host` varchar(300) DEFAULT NULL,
  `images_ftp_login` varchar(300) DEFAULT NULL,
  `images_ftp_password` varchar(300) DEFAULT NULL,
  `images_ftp_active` int(1) DEFAULT 0,
  `images_file_path` varchar(300) DEFAULT NULL,
  `profile_method` int(1) DEFAULT 0,
  PRIMARY KEY (`profile_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
");

 

$installer->endSetup();