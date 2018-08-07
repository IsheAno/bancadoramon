<?php

class Wyomind_Massproductimport_Block_Adminhtml_Import_Edit_Tabs extends Wyomind_Massstockupdate_Block_Adminhtml_Import_Edit_Tabs
{

    public $module = "massproductimport";

    public function __construct()
    {
        parent::__construct();
       
        $this->setTitle('Mass Product Import & Update');
    }

   

}
