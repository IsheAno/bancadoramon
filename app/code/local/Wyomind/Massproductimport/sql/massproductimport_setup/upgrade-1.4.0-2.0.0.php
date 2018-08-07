<?php

$updater = $this;
$updater->startSetup();

$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `create_category_onthefly` INT(1) DEFAULT 0");

$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `category_is_active` INT(1) DEFAULT 0");

$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `category_include_in_menu` INT(1) DEFAULT 0");

$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `category_parent_id` INT(11) DEFAULT " . Mage_Catalog_Model_Category::TREE_ROOT_ID);


$updater->run(
        "INSERT INTO {$this->getTable('massproductimport_profile')} (  `profile_name` ,  `file_path`,  `file_separator` ,  `file_enclosure` ,  `auto_set_instock` ,  `mapping` ,  `cron_setting` ,
  `imported_at` ,  `sku_offset`,  `use_custom_rules` ,  `custom_rules` ,  `identifier_code` ,  `file_system_type` ,  `use_sftp` ,  `ftp_host` ,
  `ftp_login`,  `ftp_password` ,  `ftp_active` ,  `ftp_dir` ,  `file_type` ,  `xpath_to_product` ,  `default_values` ,  `profile_method` ,  `preserve_xml_column_mapping` ,
  `xml_column_mapping` ,  `sql` ,  `sql_path`,   `sql_file` )"
        . " SELECT   `profile_name` ,  `file_path`,  `file_separator` ,  `file_enclosure` ,  `auto_set_instock` ,  `mapping` ,  `cron_setting` ,
  `imported_at` ,  `sku_offset`,  `use_custom_rules` ,  `custom_rules` ,  `identifier_code` ,  `file_system_type` ,  `use_sftp` ,  `ftp_host` ,
  `ftp_login`,  `ftp_password` ,  `ftp_active` ,  `ftp_dir` ,  `file_type` ,  `xpath_to_product` ,  `default_values` ,  `profile_method` ,  `preserve_xml_column_mapping` ,
  `xml_column_mapping` ,  `sql` ,  `sql_path`,   `sql_file` FROM {$this->getTable('massstockupdate_profile')};"
);
$updater->endSetup();
