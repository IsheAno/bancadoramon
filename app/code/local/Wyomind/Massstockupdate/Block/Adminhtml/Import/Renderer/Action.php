<?php

class Wyomind_Massstockupdate_Block_Adminhtml_Import_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{

    public $module = "massstockupdate";

    public function render(Varien_Object $row)
    {
        $this->getColumn()->setActions(
                array(
                    array(
                        'url' => $this->getUrl('*/' . $this->module . '/edit', array('profile_id' => $row->getProfile_id())),
                        'caption' => Mage::helper($this->module)->__('Edit'),
                    ),
                    array(
                        'url' => $this->getUrl('*/' . $this->module . '/delete', array('profile_id' => $row->getProfile_id())),
                        'confirm' => Mage::helper($this->module)->__('Are you sure you want to delete this profile ?'),
                        'caption' => Mage::helper($this->module)->__('Delete'),
                    ),
                    array(
                        'url' => "javascript:updater.generate('" . $this->getUrl('*/'.$this->module.'/run', array('profile_id' => $row->getProfile_id())) . "')",
                        'confirm' => Mage::helper($this->module)->__('All data will be updated. Continue anyway?'),
                        'caption' => Mage::helper($this->module)->__('Run profile'),
                    ),
                )
        );
        return parent::render($row);
    }

}
