<?php

class Wyomind_Massproductimport_Model_Mysql4_Attribute extends Wyomind_Massstockupdate_Model_Mysql4_Abstract
{

    private $_attributeLabels = array();
    private $taxClasses = array();

    const LABEL_SEPARATOR = ",";

    public $labelVisibilty = array(
        "not visible individually" => 1,
        "catalog" => 2,
        "search" => 3,
        "catalog and search" => 4
    );

    public function getTaxClasses()
    {
// tax class
        $taxClasseList = array();
        $taxClasses = Mage::getModel("tax/class")->getCollection()->addFieldToFilter("class_type", array("eq" => "PRODUCT"));
        foreach ($taxClasses as $taxClass) {
            $taxClasseList[strtolower($taxClass->getClassName())] = $taxClass->getClassId();
        }
        $taxClasseList["none"] = 0;
        return $taxClasseList;
    }

    public function beforeCollect($profile, $columns)
    {
        $ids = array();

        foreach ($columns["Attribute"] as $column) {
            $ids[] = $column[1];
        }

        $this->tableEaov = Mage::getSingleton("core/resource")->getTableName('eav_attribute_option_value');
        $this->tableEao = Mage::getSingleton("core/resource")->getTableName('eav_attribute_option');

// collect dropdown and swatch attribute
        $fields = array("frontend_input");
        $conditions = array(
            array("in" =>
                array(
                    "select", "multiselect"
                )
            ),
        );
        $atributes = $this->getAttributesList($fields, $conditions, false);
        foreach ($atributes as $attribute) {


            $this->selectAttributes[$attribute["attribute_id"]] = array(
                "attribute_code" => $attribute["attribute_code"],
            );
        }



// collect all option for dropdown attribute
        if (count($ids)) {
            $read = Mage::getSingleton("core/resource")->getConnection("core_read");

            $select = "   SELECT eao.option_id,value,store_id,attribute_id FROM " . $this->tableEao . " AS eao
       INNER JOIN " . $this->tableEaov . " AS eaov ON eao.option_id = eaov.option_id
       WHERE eao.attribute_id in(" . implode(',', $ids) . ") AND store_id=0";

            $dropdownLabels = $read->fetchAll($select);


            foreach ($dropdownLabels as $attributeLabel) {
                $attributeId = $attributeLabel["attribute_id"];

                $value = trim(strtolower($attributeLabel["value"]));
                $optionId = $attributeLabel["option_id"];
                $this->_attributeLabels[$attributeId][$value] = $optionId;
            }
        }


        $this->taxClasses = $this->getTaxClasses();

        parent::beforeCollect($profile, $columns);
    }

    public function collect($productId, $value, $strategy, $profile)
    {

        list($entityType, $attributeId) = $strategy['option'];
        $attribute_code = null;
        if (isset($strategy['option'][2])) {
            $attribute_code = $strategy['option'][2];
        }
        $table = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity_" . $entityType);


        switch ($attribute_code) {

            case "visibility":
                $value = strtolower($value);
                $visibility_label = $this->labelVisibilty;
                if (isset($visibility_label[$value])) {
                    $value = $visibility_label[$value];
                }

                if (!array_search($value, $visibility_label)) {
                    $value = 1;
                }
                $strategy['storeviews'] = array(0);
                break;
            case "tax_class_id":
                if ($value == '') {
                    return;
                }
                $value = strtolower($value);
                if (isset($this->taxClasses[$value])) {
                    $value = $this->taxClasses[$value];
                }
                if (!array_search($value, $this->taxClasses)) {
                    $value = 0;
                }

                $strategy['storeviews'] = array(0);
                break;
            case "status":

                $value = $this->getValue($value);
                if ($value == 0) {
                    $value = 2;
                }
                $strategy['storeviews'] = array(0);
                break;


            default:
                $values = explode(self::LABEL_SEPARATOR, $value);
                $val = array();

                if ($entityType == "int") {
                    $value = (int) $this->getValue($value);
                }
                foreach ($values as $value) {
                    $value = trim($value);
                    if ($value == "") {
                        foreach ($strategy['storeviews'] as $storeview) {
                            $data = array(
                                "entity_id" => $productId,
                                "entity_type_id" => parent::ENTITY_TYPE_ID,
                                "store_id" => $storeview,
                                "attribute_id" => $attributeId
                            );
                            $this->queries[$this->queryIndexer][] = $this->_delete($table, $data);
                        }
                        return;
                    }
// if attribute is dropdown, swatch, multiselect 
                    if (isset($this->selectAttributes[$attributeId])) {
// if the option_id exists for this label  
                        if (isset($this->_attributeLabels[$attributeId][trim(strtolower($value))])) {
                            $val[] = "'" . str_replace("'", "''", $this->_attributeLabels[$attributeId][trim(strtolower($value))]) . "'";
                        }
//else the option_id and label is inserted
                        else {
// if option_id not yet added
                            if (!isset($this->_OptionIdRegistry[md5($attributeId . $value)])) {
                                $this->queries[$this->queryIndexer][] = "INSERT INTO `$this->tableEao` (`attribute_id`) VALUES ( '$attributeId');";
// insert new value for dropdown
                                $this->queries[$this->queryIndexer][] = "INSERT INTO `$this->tableEaov` (`option_id`,`value`) VALUES ( LAST_INSERT_ID(),'" . str_replace("'", "''", $value) . "');";

                                $this->_OptionIdRegistry[md5($attributeId . $value)] = true;
                            }
                            $val[] = "(SELECT eao.option_id FROM `$this->tableEao`  eao INNER JOIN `$this->tableEaov`  eaov ON eao.option_id=eaov.option_id WHERE attribute_id='$attributeId' AND value='" . str_replace("'", "''", $value) . "' LIMIT 1) ";
                        }
                    }
// basic attribute 
                    else {

                        $val[] = "'" . str_replace("'", "''", $value) . "'";
                    }
                }
                if (!count($val)) {
                    return;
                }
                $value = "CONCAT(" . implode(",',',", $val) . ")";
                break;
        }



        foreach ($strategy['storeviews'] as $storeview) {
            $data = array(
                "entity_id" => $productId,
                "entity_type_id" => parent::ENTITY_TYPE_ID,
                "store_id" => $storeview,
                "attribute_id" => $attributeId,
                "value" => trim($value)
            );
            $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($table, $data);
        }


        parent::collect($productId, $value, $strategy, $profile);
    }

    public function getDropdown()
    {

        /* ATTIBUTE MAPPING */
        $dropdown = array();

        $attributesList = $this->getAttributesList();

        $i = 0;
        foreach ($attributesList as $attribute) {
            if (!empty($attribute['frontend_label'])) {
                $dropdown['Attributes'][$i]['label'] = $attribute['frontend_label'];
                $dropdown['Attributes'][$i]['id'] = "Attribute/" . $attribute['backend_type'] . "/" . $attribute['attribute_id'];
                $dropdown['Attributes'][$i]['style'] = "attribute storeviews-dependent";
                if ($attribute["frontend_input"] == "select") {
                    $dropdown['Attributes'][$i]['type'] = "Option value name (case sensitive)";
                } elseif ($attribute["frontend_input"] == "multiselect") {
                    $dropdown['Attributes'][$i]['type'] = "Option value names (case sensitive) separated by " . self::LABEL_SEPARATOR;
                } else {
                    $dropdown['Attributes'][$i]['type'] = $this->{$attribute['backend_type']};
                }
                $i++;
            }
        }
        return $dropdown;
    }

    public function getIndexes($mapping)
    {


        foreach ($mapping as $map) {
            $strategy = explode("/", $map->id);


            if (isset($strategy[3]) && $strategy[3] == "url_key") {

                return [10 => "catalog_url"];
            }
        }

        return [];
    }

}
