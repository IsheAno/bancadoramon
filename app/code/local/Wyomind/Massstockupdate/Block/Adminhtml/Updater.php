<?php

/**
 * Copyright © 2016 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */
class Wyomind_Massstockupdate_Block_Adminhtml_Updater extends Mage_Adminhtml_Block_Template
{

    public $module = "massstockupdate";

    public function _ToHtml()
    {

    
        $json = array();
        $data = Mage::helper('core')->jsonDecode($this->getRequest()->getPost('data'));
        foreach ($data as $f) {
            $row = new Varien_Object;
            $row->setId($f[0]);
            $row->setCronSetting($f[1]);
            $object = "Wyomind_" . ucfirst($this->module) . "_Block_Adminhtml_Import_Renderer_Status";
            $status = new $object();
            $json[] = array("id" => $f[0], "content" => ($status->render($row)));
        }
        return (Mage::helper('core')->jsonEncode($json));
    }

}
