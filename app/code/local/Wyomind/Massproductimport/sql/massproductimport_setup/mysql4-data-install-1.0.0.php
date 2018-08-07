<?php

$installer = $this;
$installer->startSetup();
$sample = array(
    "profile_name" => "XML_sample",
    "file_path" => "/var/sample/update.xml",
    "file_separator" => "",
    "file_enclosure" => "",
    "auto_set_instock" => "0",
    "mapping" => "[{\"id\":\"Attribute/varchar/71\",\"label\":\"Attributes | Name\",\"index\":\"1\",\"source\":\"name\",\"default\":\"\",\"scripting\":\"\",\"storeviews\":[\"0\"],\"enabled\":true},{\"id\":\"Price/decimal/75\",\"label\":\"Price | Price\",\"index\":\"3\",\"source\":\"price\",\"default\":\"\",\"scripting\":\"\",\"storeviews\":[\"0\"],\"enabled\":true},{\"id\":\"Attribute/text/72\",\"label\":\"Attributes | Description\",\"index\":\"2\",\"source\":\"description\",\"default\":\"\",\"scripting\":\"\",\"storeviews\":[\"0\"],\"enabled\":true}]",
    "cron_setting" => "{\"days\":[],\"hours\":[]}",
    "imported_at" => date("y-m-d H:i:s"),
    "sku_offset" => "0",
    "use_custom_rules" => "0",
    "custom_rules" => "<?php",
    "identifier_code" => "sku",
    "file_system_type" => "0",
    "use_sftp" => "",
    "ftp_host" => "",
    "ftp_login" => "",
    "ftp_password" => "",
    "ftp_active" => "",
    "ftp_dir" => "",
    "file_type" => "1",
    "xpath_to_product" => "/products/item",
    "default_values" => "[]",
    
    "profile_method" => "0",
);

$profile = Mage::getSingleton('massproductimport/import')->setData($sample);
$profile->save();

$installer->endSetup();
