<?php

class Wyomind_Massstockupdate_Block_Adminhtml_Import_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public $module = "massstockupdate";

    public function __construct()
    {

        parent::__construct();
        $this->setId($this->module . 'Import');
        $this->setDefaultSort($this->module . '_profileprofiles_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {

        $collection = Mage::getModel($this->module . '/import')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('profile_id', array(
            'header' => Mage::helper($this->module)->__('ID'),
            'align' => 'right',
            'index' => 'profile_id',
            'filter' => false
        ));

        $this->addColumn('profile_name', array(
            'header' => Mage::helper($this->module)->__('Profile name'),
            'align' => 'left',
            'index' => 'profile_name',
            "width" => '250px'
        ));
        
        $this->addColumn('file_system_type', array(
            'header' => Mage::helper($this->module)->__('File location'),
            'align' => 'left',
            'index' => 'file_system_type',
            'type' => 'options',
            'options' => array(
                0 => 'Magento File System',
                1 => 'Ftp server',
                2 => 'Url',
                3 => 'Dropbox'
            )
        ));

        $this->addColumn('file_type', array(
            'header' => Mage::helper($this->module)->__('File type'),
            'align' => 'left',
            'index' => 'file_type',
            'type' => 'options',
            'options' => array(
                0 => __('csv'),
                1 => __('xml')
            )
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper($this->module)->__('Status'),
            'align' => 'left',
            'index' => 'status',
            "width" => '100px',
            'renderer' => 'Wyomind_' . ucfirst($this->module) . '_Block_Adminhtml_Import_Renderer_Status'
        ));
        
        $this->addColumn('imported_at', array(
            'header' => Mage::helper($this->module)->__('Last execution'),
            'align' => 'left',
            'index' => 'imported_at',
            'type' => "datetime"
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper($this->module)->__('Action'),
            'align' => 'left',
            'index' => 'action',
            'filter' => false,
            'sortable' => false,
            'renderer' => 'Wyomind_' . ucfirst($this->module) . '_Block_Adminhtml_Import_Renderer_Action'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('profile_id' => $row->getProfileId()));
    }
}