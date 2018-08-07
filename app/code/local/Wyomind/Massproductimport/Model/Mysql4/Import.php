<?php

class Wyomind_Massproductimport_Model_Mysql4_Import extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {

        // Note that the massproductimport_id refers to the key field in your database table.

        $this->_init('massproductimport/import', 'profile_id');
    }

  

}
