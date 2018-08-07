<?php

class Wyomind_Massproductimport_Model_Mysql4_ConfigurableProductsImage extends Wyomind_Massproductimport_Model_Mysql4_Image
{

    public function getDropdown()
    {


        $dropdown = parent::getDropdown();
        foreach ($dropdown['Images'] as $key => $attribute) {
            $dropdown['Images (configurable product)'][$key] = $attribute;
            $dropdown['Images (configurable product)'][$key]["id"] = "ConfigurableProducts" . $dropdown['Images (configurable product)'][$key]["id"];
            $dropdown['Images (configurable product)'][$key]["style"].=" configurable-product";
        }
        return $dropdown;
    }

    function getFields($fieldset = null, $form = null, $class = null)
    {
        return false;
    }

}
