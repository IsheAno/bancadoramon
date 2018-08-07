<?php

class Wyomind_Massstockupdate_Block_Adminhtml_Import extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public $module = "massstockupdate";

    public function __construct()
    {

        $this->_controller = 'adminhtml_import';
        $this->_blockGroup = $this->module;
        $this->_headerText = Mage::helper($this->module)->__('Mass Stock Update');
        $this->_addButtonLabel = Mage::helper($this->module)->__('Create a new profile');
        parent::__construct();
    }

    public function isSingleStoreMode()
    {
        if (!Mage::app()->isSingleStoreMode()) {
            return false;
        }
        return true;
    }

}
