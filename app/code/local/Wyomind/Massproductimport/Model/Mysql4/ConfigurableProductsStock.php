<?php

class Wyomind_Massproductimport_Model_Mysql4_ConfigurableProductsStock extends Wyomind_Massproductimport_Model_Mysql4_Stock
{

    public function getDropdown()
    {
        $dropdown = array();
        /* STOCK MAPPING */
       
        


        $dropdown = parent::getDropdown();
        foreach ($dropdown['Stocks'] as $key => $attribute) {
            $dropdown['Stocks (configurable product)'][$key] = $attribute;
            $dropdown['Stocks (configurable product)'][$key]["id"] = "ConfigurableProducts" . $dropdown['Stocks (configurable product)'][$key]["id"];
            $dropdown['Stocks (configurable product)'][$key]["style"].=" configurable-product";
        }
        return $dropdown;
    }

    function getFields($fieldset = null, $form = null, $class = null)
    {
        return false;
    }

    public function prepareQueries($productId, $profile)
    {




        $data["product_id"] = $productId;
        $data["stock_id"] = "1";
        foreach ($this->fields as $field => $value) {
            $data[$field] = $this->getValue($value);
        }

        $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->table, $data);
    }

}
