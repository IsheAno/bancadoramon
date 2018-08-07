<?php

class Wyomind_Massproductimport_Model_Mysql4_Price extends Wyomind_Massproductimport_Model_Mysql4_Attribute
{

    CONST FIELD_SEPARATOR = "|";
    CONST LINE_SEPARATOR = "~";

    protected $_tierPriceAttributeId;
    protected $_groupPriceAttributeId;
    protected $_weeeAttributeIds = [];
    public $queries = array();

    public function _construct()
    {

        $this->tableCepgp = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity_group_price");
        $this->tableCeptp = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity_tier_price");
        $this->tableWt = Mage::getSingleton("core/resource")->getTableName("weee_tax");

        parent::_construct();
    }

    public function beforeCollect($profile, $columns)
    {
        $websites = Mage::app()->getWebsites();
        $data = [];
        foreach ($websites as $website) {
            $storegroups = $website->getGroupCollection();
            foreach ($storegroups as $storegroup) {
                $storeviews = $storegroup->getStoreCollection();
                foreach ($storeviews as $storeview) {
                    $data[$storeview->getStoreId()] = $website->getId();
                }
            }
        }
        $this->website = $data;
        // Tiers price
        $fields = array("backend_model");
        $conditions = array(
            array("eq" => "catalog/product_attribute_backend_tierprice")
        );
        $attributes = $this->getAttributesList($fields, $conditions, false);
        if (count($attributes)) {
            $this->_tierPriceAttributeId = $attributes[0]["attribute_id"];
        }
        // Group price
        $fields = array("backend_model");
        $conditions = array(
            array("eq" => "catalog/product_attribute_backend_groupprice")
        );
        $attributes = $this->getAttributesList($fields, $conditions, false);
        if (count($attributes)) {
            $this->_groupPriceAttributeId = $attributes[0]["attribute_id"];
        }
        // Fixed taxes
        $fields = array("backend_model");
        $conditions = array(
            array("eq" => "weee/attribute_backend_weee_tax"),
        );
        $atributes = $this->getAttributesList($fields, $conditions, false);

        foreach ($atributes as $attribute) {
            $this->_weeeAttributeIds[] = $atributes[0]["attribute_id"];
        }
    }

    public function collect($productId, $value, $strategy, $profile)
    {
        list($entityType, $attributeId) = $strategy['option'];
        if ($attributeId == $this->_tierPriceAttributeId) {
            // Collect tiers price
            $prices = explode(self::LINE_SEPARATOR, $value);
            // Delete old data
            $this->queries[$this->queryIndexer][] = "DELETE FROM " . $this->tableCeptp . " WHERE entity_id = $productId;";
            $storeviews = $strategy["storeviews"];
            $websites = [];
            // Prepare and insert new data
            foreach ($prices as $price) {
                if (trim($price) != "") {
                    list($groupId, $qty, $val) = explode(self::FIELD_SEPARATOR, $price);
                    $allGroup = 0;
                    if ($groupId == "*") {
                        $allGroup = 1;
                    }
                    foreach ($storeviews as $storeview) {
                        if (!in_array($this->website[$storeview], $websites)) {
                            $websiteId = 0;
                            if ($storeview != 0) {
                                $websiteId = $this->website[$storeview];
                            }
                            $websites[] = $websiteId;
                            $this->queries[$this->queryIndexer][] = "INSERT INTO " . $this->tableCeptp . " (entity_id,all_groups,customer_group_id,qty,value,website_id) "
                                    . " VALUES ($productId,'$allGroup','$groupId','$qty','$val','" . $websiteId . "')\n ";
                        }
                    }
                }
            }
        } else if ($attributeId == $this->_groupPriceAttributeId) {
            // Collect group price
            $prices = explode(self::LINE_SEPARATOR, $value);
            // Delete old data
            $this->queries[$this->queryIndexer][] = "DELETE FROM " . $this->tableCepgp . " WHERE entity_id = $productId;";
            $storeviews = $strategy["storeviews"];
            $websites = [];
            // Prepare and insert new data
            foreach ($prices as $price) {
                if (trim($price) != "") {
                    list($groupId, $val) = explode(self::FIELD_SEPARATOR, $price);
                    $allGroup = 0;
                    if ($groupId == "*") {
                        $allGroup = 1;
                    }
                    $isPercent = 0;
                    if (substr($val, -1, 1) == "%") {
                        $isPercent = 1;
                    }
                    foreach ($storeviews as $storeview) {
                        if (!in_array($this->website[$storeview], $websites)) {
                            $websiteId = 0;
                            if ($storeview != 0) {
                                $websiteId = $this->website[$storeview];
                            }
                            $websites[] = $websiteId;
                            $this->queries[$this->queryIndexer][] = "INSERT INTO " . $this->tableCepgp . " (entity_id,all_groups,customer_group_id,value,website_id,is_percent) "
                                    . " VALUES ($productId,'$allGroup','$groupId','$val','" . $websiteId . "','" . $isPercent . "')\n ";
                        }
                    }
                }
            }
        } else if (in_array($attributeId, $this->_weeeAttributeIds)) {
            // Collect fixed taxes
            $weees = explode(self::LINE_SEPARATOR, $value);
            // Delete old data
            $this->queries[$this->queryIndexer][] = "DELETE FROM " . $this->tableWt . " WHERE entity_id = $productId and attribute_id='" . $attributeId . "';";
            $storeviews = $strategy["storeviews"];
            $websites = [];
            // Prepare and insert new data
            foreach ($weees as $weee) {
                if (trim($weee) != "") {
                    list($country, $region, $tax) = explode(self::FIELD_SEPARATOR, $weee);
                    if ($region == "*") {
                        $region = 0;
                    }
                    foreach ($storeviews as $storeview) {
                        if (!in_array($this->website[$storeview], $websites)) {
                            $websiteId = 0;
                            if ($storeview != 0) {
                                $websiteId = $this->website[$storeview];
                            }
                            $websites[] = $websiteId;
                            $this->queries[$this->queryIndexer][] = "INSERT INTO " . $this->tableWt . " (entity_id,country,value,state,website_id,attribute_id) "
                                    . " VALUES ($productId,'$country','$tax','$region','" . $websiteId . "','" . $attributeId . "')\n ";
                        }
                    }
                }
            }
        } else {
//            if (trim($value) == "") {
//                return;
//            }
            parent::collect($productId, $value, $strategy, $profile);
        }
    }

    public function getDropdown()
    {

        /* ATTIBUTE MAPPING */
        $dropdown = array();
        $fields = array("backend_model", "backend_model", "attribute_code", "attribute_code");
        $conditions = array(
            array("like" =>
                array(
                    "%price%"
                )
            ),
            array("like" =>
                array(
                    "%weee%"
                ),
            ),
            array("eq" =>
                array(
                    "special_to_date"
                )
            ),
            array("eq" =>
                array(
                    "special_from_date"
                )
            )
        );
        $attributesList = $this->getAttributesList($fields, $conditions, false);
        $i = 0;
        foreach ($attributesList as $attribute) {
            if (!empty($attribute['frontend_label'])) {
                if ($attribute['attribute_code'] == "tier_price") {
                    $dropdown['Price'][$i]['label'] = __("Tier Price");
                    
                } else {
                    $dropdown['Price'][$i]['label'] = $attribute['frontend_label'];
                }
                $dropdown['Price'][$i]['id'] = "Price/" . $attribute['backend_type'] . "/" . $attribute['attribute_id'];
                $dropdown['Price'][$i]['style'] = "price storeviews-dependent";
                if ($attribute["backend_model"] == "weee/attribute_backend_weee_tax") {

                    $dropdown['Price'][$i]['type'] = "List of fixed tax prices separated by " . self::LINE_SEPARATOR;
                    $dropdown['Price'][$i]['value'] = "[Country code 1]" . self::FIELD_SEPARATOR . "[Region Code 1]" . self::FIELD_SEPARATOR . "[Tax price 1]" . self::LINE_SEPARATOR . "[Country code 2]" . self::FIELD_SEPARATOR . "[Region Code 2]" . self::FIELD_SEPARATOR . "[Tax price 2]" . self::LINE_SEPARATOR . "...";
                } elseif ($attribute['attribute_code'] == "tier_price") {
                    $dropdown['Price'][$i]['type'] = "List of tier prices separated by " . self::LINE_SEPARATOR;
                    $dropdown['Price'][$i]['value'] = "[Group id 1]" . self::FIELD_SEPARATOR . "[Qty 1]" . self::FIELD_SEPARATOR . "[Price 1]" . self::LINE_SEPARATOR . "[Group Id 2]" . self::FIELD_SEPARATOR . "[Qty 2]" . self::FIELD_SEPARATOR . "[Price 2]" . self::LINE_SEPARATOR . "...";
                } elseif ($attribute['attribute_code'] == "group_price") {
                    $dropdown['Price'][$i]['type'] = "List of group prices separated by " . self::LINE_SEPARATOR;
                    $dropdown['Price'][$i]['value'] = "[Group id 1]" . self::FIELD_SEPARATOR . "[Price 1]" . self::LINE_SEPARATOR . "[Group Id 2]" . self::FIELD_SEPARATOR . "[Price 2]" . self::LINE_SEPARATOR . "...";
                } else {

                    $dropdown['Price'][$i]['type'] = $this->{$attribute["backend_type"]};
                }
                $i++;
            }
        }
        return $dropdown;
    }

    public function getIndexes($mapping)
    {
        return [9 => "catalog_product_price"];
    }

}
