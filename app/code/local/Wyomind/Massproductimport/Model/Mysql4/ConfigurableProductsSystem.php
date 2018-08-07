<?php

class Wyomind_Massproductimport_Model_Mysql4_ConfigurableProductsSystem extends Wyomind_Massproductimport_Model_Mysql4_System
{

    const ITEM_SEPARATOR = ",";

    private $_configurableAttributes = array();
    private $_configurableAttributeLabels = array();

    public function _construct()
    {



        $this->tableCpsa = Mage::getSingleton("core/resource")->getTableName("catalog_product_super_attribute");
        $this->tableCpsal = Mage::getSingleton("core/resource")->getTableName("catalog_product_super_attribute_label");

        parent::_construct();
    }

    public function reset()
    {
        $this->fields = array();
    }

    public function beforeCollect($profile, $columns)
    {
        $fields = array("frontend_input");
        $conditions = array(
            array("eq" =>
                array(
                    "select",
                )
            ),
        );
        $atributes = $this->getAttributesList($fields, $conditions, false);
        foreach ($atributes as $attribute) {
            $this->_configurableAttributes[$attribute["attribute_id"]] = $attribute["attribute_code"];
            $this->_configurableAttributeLabels[$attribute["attribute_id"]] = $attribute["frontend_label"];
        }
        parent::beforeCollect($profile, $columns);
      
    }

    function collect($productId, $value, $strategy, $profile)
    {


        list($field) = $strategy['option'];
        switch ($field) {

            case "attributes":

                $values = explode(self::ITEM_SEPARATOR, $value);

                foreach ($values as $value) {

                   

                    if (isset($this->_configurableAttributes[$value]) || in_array($value, $this->_configurableAttributes)) {
                       
                        if (in_array($value, $this->_configurableAttributes)) {
                            $value = array_search($value, $this->_configurableAttributes);
                        }

                        $label = $this->_configurableAttributeLabels[$value];
                        $data = array(
                            "product_id" => $productId,
                            "attribute_id" => "'" . $value . "'",
                        );
                        $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->tableCpsa, $data);

                        $data = array(
                            "product_super_attribute_id" => "(SELECT  product_super_attribute_id FROM " . $this->tableCpsa . " WHERE product_id=$productId AND attribute_id='" . $value . "')",
                            "value" => "'" . $label . "'",
                        );
                        $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->tableCpsal, $data);
                    }
                  
                }

                return;
                break;

            default:

                $this->fields["type_id"] = "configurable";
                parent::collect($productId, $value, $strategy, $profile);
                break;
        }
    }

    public function getDropdown()
    {



        $dropdown = parent::getDropdown();
        foreach ($dropdown['Required attributes'] as $key => $attribute) {
            $dropdown['Required attributes (configurable product)'][$key] = $attribute;
            $dropdown['Required attributes (configurable product)'][$key]["id"] = "ConfigurableProducts" . $dropdown['Required attributes (configurable product)'][$key]["id"];
            $dropdown['Required attributes (configurable product)'][$key]["style"].=" configurable-product";
        }
        return $dropdown;
    }

    function getFields($fieldset = null, $form = null, $class = null)
    {
        return;
    }

}
