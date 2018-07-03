<?php

class INT_Lowstock_Model_Mysql4_Lowstock_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('lowstock/lowstock');
    }
}