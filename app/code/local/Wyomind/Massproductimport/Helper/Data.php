<?php

class Wyomind_Massproductimport_Helper_Data extends Wyomind_Massstockupdate_Helper_Data
{

    public $module = "massproductimport";

    const TEMP_DIR = "var/tmp/";
    const UPLOAD_DIR = "var/upload/";
    const TEMP_PREFIX = "MassProductImport_";
    const TEMP_EXT = ".tmp";
    const LOG_FILE = "MassProductImport.log";
    const ADDITIONAL_ATTR = "";

    public $modules = array(
        10 => "System",
        20 => "Price",
        30 => "AdvancedInventory",
        40 => "Stock",
        50 => "Attribute",
        60 => "CustomOption",
        70 => "Image",
        80 => "Category",
        90 => "Merchandising",
        100 => "DownloadableProduct",
        110 => "ConfigurableProduct",
        11 => "ConfigurableProductsSystem",
        41 => "ConfigurableProductsStock",
        51 => "ConfigurableProductsAttribute",
        71 => "ConfigurableProductsImage",
        81 => "ConfigurableProductsCategory",
        1000 => "Ignored"
    );

}
