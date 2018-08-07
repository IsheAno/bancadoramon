<?php

class Wyomind_Massproductimport_Block_Adminhtml_Import extends Wyomind_Massstockupdate_Block_Adminhtml_Import
{

    public $module = "massproductimport";

    public function __construct()
    {
        parent::__construct();
        $this->_headerText = Mage::helper('massproductimport')->__('Mass Product Import & Update');
    }

}
