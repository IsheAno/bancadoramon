<?php


$installer = $this;
$installer->startSetup();
$sample = array(
   "profile_name"=>"inventory_update",
  "file_path"=>"/var/sample/inventory.csv",
  "file_separator"=>";",
  "file_enclosure"=>"",
  "auto_set_instock"=>"0",
  "mapping"=>"[{\"id\":\"Stock/is_in_stock\",\"label\":\"Stocks | Is In Stock\",\"index\":\"1\",\"source\":\"status\",\"default\":\"\",\"scripting\":\"\",\"storeviews\":[\"0\"],\"enabled\":true},{\"id\":\"Stock/qty\",\"label\":\"Stocks | Qty\",\"index\":\"2\",\"source\":\"quantity\",\"default\":\"\",\"scripting\":\"\",\"storeviews\":[\"0\"],\"enabled\":true}]",
  "cron_setting"=>"{\"days\":[],\"hours\":[]}",
  "imported_at"=>date("y-m-d H:i:s"),
  "sku_offset"=>"0",
  "use_custom_rules"=>"0",
  "custom_rules"=>"",
  "identifier_code"=>"sku",
  "file_system_type"=>"0",
  "use_sftp"=>"", 
  "ftp_host"=>"",
  "ftp_login"=>"",
  "ftp_password"=>"",
  "ftp_active"=>"",
  "ftp_dir"=>"",
  "file_type"=>"0",
  "xpath_to_product"=>"/products/product",
  "default_values"=>"[]",
);

$profile = Mage::getSingleton('massstockupdate/import')->setData($sample);
$profile->save();

$installer->endSetup();
