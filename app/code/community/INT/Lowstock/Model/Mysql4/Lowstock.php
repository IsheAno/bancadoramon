<?php

class INT_Lowstock_Model_Mysql4_Lowstock extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the lowstock_id refers to the key field in your database table.
        $this->_init('lowstock/lowstock', 'lowstock_id');
    }
}