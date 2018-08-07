<?php

class Wyomind_Massstockupdate_Block_Adminhtml_Import_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public $module = "massstockupdate";

    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'profile_id';
        $this->_controller = 'adminhtml_import';
        $this->_blockGroup = $this->module;

        
        
        if (Mage::registry($this->module.'_data')->getProfileId()) {

            $this->_addButton('import', array(
                'label' => Mage::helper('adminhtml')->__('Run profile now'),
                'onclick' => 'if(confirm(\'' . Mage::helper($this->module)->__('All data will be updated. Continue anyway?') . '\')){$(\'run\').value=1; editForm.submit();}',
                'class' => 'delete save',
            ), 99);
        }
    }

    public function getHeaderText()
    {
        if (Mage::registry($this->module.'_data') && Mage::registry($this->module.'_data')->getProfileId()) {
            return Mage::helper($this->module)->__("Edit profile  '%s'", $this->htmlEscape(Mage::registry($this->module.'_data')->getProfile_name()));
        } else {
            return Mage::helper($this->module)->__('New profile');
        }
    }
}