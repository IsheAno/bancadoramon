<?php

class Wyomind_Massstockupdate_Block_Adminhtml_Import_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public $module = "massstockupdate";

    public function __construct()
    {
        parent::__construct();
        $this->setId($this->module.'_profile');
        $this->setDestElementId('edit_form');
        $this->setTitle('Mass Stock Update');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_setting', array(
            'label' => $this->__('Settings'),
            'title' => $this->__('Settings'),
            'content' => $this->getLayout()
                    ->createBlock($this->module.'/adminhtml_import_edit_tab_setting')
                    ->toHtml()
        ));
        $this->addTab('form_advanced', array(
            'label' => $this->__('Advanced Settings'),
            'title' => $this->__('Advanced Settings'),
            'content' => $this->getLayout()
                    ->createBlock($this->module.'/adminhtml_import_edit_tab_advanced')
                    ->toHtml()
        ));
        $this->addTab('form_mapping', array(
            'label' => $this->__('Mapping & Rules'),
            'title' => $this->__('Mapping & Rules'),
            'content' => $this->getLayout()
                    ->createBlock($this->module.'/adminhtml_import_edit_tab_mapping')
                    ->toHtml()
        ));


        $this->addTab('form_template', array(
            'label' => $this->__('Scheduled tasks'),
            'title' => $this->__('Scheduled tasks'),
            'content' => $this->getLayout()
                    ->createBlock($this->module.'/adminhtml_import_edit_tab_cron')
                    ->toHtml()
        ));

        return parent::_beforeToHtml();
    }

}
