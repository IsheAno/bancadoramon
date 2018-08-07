<?php

/**
 * Copyright © 2016 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */
class Wyomind_Massstockupdate_Block_Adminhtml_Uploader extends Mage_Adminhtml_Block_Template
{

    public $module = "massstockupdate";

    public function _ToHtml()
    {

        try {

            $json = array();

            $uploader = new Varien_File_Uploader("file_upload");
            $uploader->setAllowedExtensions(array("txt", "csv", "xml"));


            $uploader->setFilesDispersion(false);
            $object = "Wyomind_" . ucfirst($this->module) . "_Helper_Data";
            $path = $object::UPLOAD_DIR;
            $uploader->save($path);
            $fileName = $uploader->getCorrectFileName($uploader->getUploadedFileName());
            $json["error"] = false;
            $json["message"] = $path . $fileName;

            return (Mage::helper('core')->jsonEncode($json));
        } catch (Exception $e) {
            $json["error"] = true;
            $json["message"] = $e->getMessage();
            return (Mage::helper('core')->jsonEncode($json));
        }
    }

}
