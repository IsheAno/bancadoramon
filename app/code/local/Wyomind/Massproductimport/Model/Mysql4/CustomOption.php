<?php

class Wyomind_Massproductimport_Model_Mysql4_CustomOption extends Wyomind_Massstockupdate_Model_Mysql4_Abstract
{

    CONST FIELD_SEPARATOR = "|";
    CONST LINE_SEPARATOR = "*";
    CONST OPTIONS_CONTAINER = "container1";

    public function _construct()
    {
        $this->tableCpo = Mage::getSingleton("core/resource")->getTableName("catalog_product_option");
        $this->tableCpot = Mage::getSingleton("core/resource")->getTableName("catalog_product_option_title");
        $this->tableCpotv = Mage::getSingleton("core/resource")->getTableName("catalog_product_option_type_value");
        $this->tableCpott = Mage::getSingleton("core/resource")->getTableName("catalog_product_option_type_title");
        $this->tableCpotp = Mage::getSingleton("core/resource")->getTableName("catalog_product_option_type_price");
        $this->tableCpe = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity");
        $this->tableCpev = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity_varchar");
        $this->tableEavAttr = Mage::getSingleton("core/resource")->getTableName("eav_attribute");
    }

    /**
     * Collect data for each product to udpate/import
     * @param int $productId
     * @param string $value
     * @param array $strategy
     * @param \Wyomind\MassProductImport\Model\ResourceModel\Profile $profile
     */
    public function collect($productId, $value, $strategy, $profile)
    {
        list($type) = $strategy['option'];
        $options = explode(self::LINE_SEPARATOR, $value);
        $title = trim(array_shift($options));

        $this->queries[$this->queryIndexer][] = "SET @option_id =(SELECT IFNULL((SELECT cpo.option_id FROM " . $this->tableCpo . " AS cpo "
                . "INNER JOIN " . $this->tableCpot . " AS cpot ON cpot.option_id=cpo.option_id AND title='$title' "
                . "WHERE product_id=$productId /*AND  type='$type'*/ LIMIT 1),0));";

        //$this->queries[$this->queryIndexer][] = "SET FOREIGN_KEY_CHECKS=1;";
        $this->queries[$this->queryIndexer][] = "DELETE FROM " . $this->tableCpo . " WHERE option_id = @option_id;";
        //$this->queries[$this->queryIndexer][] = "SET FOREIGN_KEY_CHECKS=0;";


        $this->queries[$this->queryIndexer][] = "INSERT INTO " . $this->tableCpo . " (product_id,type) VALUES ($productId,'$type');";
        $this->queries[$this->queryIndexer][] = "SET @option_id= LAST_INSERT_ID();";
        $this->queries[$this->queryIndexer][] = "INSERT INTO " . $this->tableCpot . " (option_id, title) VALUES(@option_id, '$title');";
        $this->queries[$this->queryIndexer][] = "SET @product_id= $productId;";
        $this->queries[$this->queryIndexer][] = "UPDATE " . $this->tableCpe . " SET has_options = 1, required_options = 1 WHERE entity_id = @product_id;";
        $data = array(
            "attribute_id" => "(select attribute_id FROM $this->tableEavAttr WHERE attribute_code='options_container')",
            "entity_id" => $productId,
            "value" => "'" . self::OPTIONS_CONTAINER . "'"
        );
        $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->tableCpev, $data);


        $options = array_filter($options);

        foreach ($options as $option) {
            list($label, $sku, $price, $position) = explode(self::FIELD_SEPARATOR, $option);
            $price_type = "fixed";
            if (stristr($price, "%")) {
                $price_type = "percent";
            }
            $price = str_replace(array("%", ","), null, $price);

            $this->queries[$this->queryIndexer][] = "INSERT INTO " . $this->tableCpotv . " (option_id, sku,sort_order)VALUES(@option_id, '" . trim($sku) . "'," . (int) $position . ");";
            $this->queries[$this->queryIndexer][] = "SET @option_type_id = LAST_INSERT_ID();";
            $this->queries[$this->queryIndexer][] = "INSERT INTO " . $this->tableCpott . " (option_type_id, title)VALUES(@option_type_id, '" . trim($label) . "');";
            $this->queries[$this->queryIndexer][] = "INSERT INTO " . $this->tableCpotp . " (option_type_id,price,price_type)VALUES(@option_type_id," . (float) trim($price) . ",'$price_type');";
        }
        parent::collect($productId, $value, $strategy, $profile);
    }

    /**
     * List of new mapping attributes
     * @return array
     */
    public function getDropdown()
    {
        $type = "Custom option title and option values separated by " . self::LINE_SEPARATOR;
        $value = " Custom option title " . self::LINE_SEPARATOR . " option label 1" . self::FIELD_SEPARATOR . "option sku 1" . self::FIELD_SEPARATOR . "option price 1" . self::FIELD_SEPARATOR . "option position 1 " . self::LINE_SEPARATOR . " option label 2" . self::FIELD_SEPARATOR . "option sku 2" . self::FIELD_SEPARATOR . "option price 2" . self::FIELD_SEPARATOR . "option position 2 " . self::LINE_SEPARATOR . "...";

        $i = 0;
        $dropdown['Custom Options'][$i]['label'] = __("Dropdown");
        $dropdown['Custom Options'][$i]["id"] = "CustomOption/drop_down";
        $dropdown['Custom Options'][$i]['style'] = "custom-option";
        $dropdown['Custom Options'][$i]['type'] = $type;
        $dropdown['Custom Options'][$i]['value'] = $value;

        $i++;

        $dropdown['Custom Options'][$i]['label'] = __("Radio");
        $dropdown['Custom Options'][$i]["id"] = "CustomOption/radio";
        $dropdown['Custom Options'][$i]['style'] = "custom-option";
        $dropdown['Custom Options'][$i]['type'] = $type;
        $dropdown['Custom Options'][$i]['value'] = $value;
        $i++;

        $dropdown['Custom Options'][$i]['label'] = __("Checkbox");
        $dropdown['Custom Options'][$i]["id"] = "CustomOption/checkbox";
        $dropdown['Custom Options'][$i]['style'] = "custom-option";
        $dropdown['Custom Options'][$i]['type'] = $type;
        $dropdown['Custom Options'][$i]['value'] = $value;
        $i++;

        $dropdown['Custom Options'][$i]['label'] = __("Muli-select");
        $dropdown['Custom Options'][$i]["id"] = "CustomOption/multiple";
        $dropdown['Custom Options'][$i]['style'] = "custom-option";
        $dropdown['Custom Options'][$i]['type'] = $type;
        $dropdown['Custom Options'][$i]['value'] = $value;
        $i++;

        return $dropdown;
    }

}
