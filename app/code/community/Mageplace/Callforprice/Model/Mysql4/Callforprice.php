<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Mageplace_Callforprice
 */

class Mageplace_Callforprice_Model_Mysql4_Callforprice extends Mage_Core_Model_Mysql4_Abstract
{
    
    protected function _construct() 
	{
        $this->_init("mageplace_callforprice/callforprice", "id");
    }
}