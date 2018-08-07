<?php

class Wyomind_Massproductimport_Model_Mysql4_ConfigurableProductsCategory extends Wyomind_Massproductimport_Model_Mysql4_Category
{

    public function getDropdown()
    {

        $dropdown = parent::getDropdown();
        foreach ($dropdown['Category'] as $key => $attribute) {
            $dropdown['Category (configurable product)'][$key] = $attribute;
            $dropdown['Category (configurable product)'][$key]["id"] = "ConfigurableProducts" . $dropdown['Category (configurable product)'][$key]["id"];
            $dropdown['Category (configurable product)'][$key]["style"].=" configurable-product";
        }
        return $dropdown;
    }

    function getFields($fieldset = null, $model = null, $form = null)
    {
        return false;
    }

}
