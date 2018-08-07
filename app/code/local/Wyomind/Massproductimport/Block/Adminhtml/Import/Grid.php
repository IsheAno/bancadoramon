<?php

class Wyomind_Massproductimport_Block_Adminhtml_Import_Grid extends Wyomind_Massstockupdate_Block_Adminhtml_Import_Grid
{

    public $module = "massproductimport";

    protected function _prepareColumns()
    {


        $this->addColumnAfter('profile_method', array(
            'header' => Mage::helper('massproductimport')->__('Method'),
            'align' => 'left',
            'index' => 'profile_method',
            'type' => 'options',
            'options' => array(
                0 => __('Update products only'),
                1 => __('Import new products only'),
                2 => __('Update products and import new products'),
            ),
                ), "file_type");






        return parent::_prepareColumns();
    }

}
