<?php

require_once(Mage::getModuleDir('controllers', 'Wyomind_Massstockupdate') . DS . 'Adminhtml' . DS . 'MassstockupdateController.php');

class Wyomind_Massproductimport_Adminhtml_MassproductimportController extends Wyomind_Massstockupdate_Adminhtml_MassstockupdateController
{

    public $module = "massproductimport";

    protected function _initAction()
    {

        $this->_title($this->__('Mass Product Import & Update'));
        $this->loadLayout();
        $this->_setActiveMenu('system/convert');

        return $this;
    }

}
