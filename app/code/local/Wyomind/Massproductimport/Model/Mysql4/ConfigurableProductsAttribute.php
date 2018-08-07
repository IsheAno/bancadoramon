<?php

class Wyomind_Massproductimport_Model_Mysql4_ConfigurableProductsAttribute extends Wyomind_Massproductimport_Model_Mysql4_Attribute
{

    public function getFields($fieldset = null, $form = false, $class = null)
    {
        return null;
    }

    public function getDropdown()
    {

        $dropdown = parent::getDropdown();
        foreach ($dropdown['Attributes'] as $key => $attribute) {
            $dropdown['Attributes (configurable product)'][$key] = $attribute;
            $dropdown['Attributes (configurable product)'][$key]["id"] = "ConfigurableProducts" . $dropdown['Attributes (configurable product)'][$key]["id"];
            $dropdown['Attributes (configurable product)'][$key]["style"].=" configurable-product";
        }
        return $dropdown;
    }

    public function prepareQueries($productId, $profile)
    {

        parent::prepareQueries();
    }

}
