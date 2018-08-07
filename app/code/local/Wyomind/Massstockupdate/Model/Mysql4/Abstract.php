<?php

class Wyomind_Massstockupdate_Model_Mysql4_Abstract extends Mage_Core_Model_Mysql4_Abstract
{

    public $module = "massstockupdate";
    public $enable = ["true", "yes", "in stock", "enable", "enabled", "1"];
    public $disable = ["false", "no", "out of stock", "disable", "disabled", "0"];
    public $decimal = "Float Number or Integer Number";
    public $datetime = "Date + Time GMT (yyyy-mm-dd hh:mm:ss)";
    public $smallint = "Boolean value";
    public $int = "Integer number";
    public $text = "Text";
    public $varchar = "Small text (255 characters maximum)";
    public $uniqueIdentifier = "Unique Identifier";

    const ENTITY_TYPE_ID = "4";
    const QUERY_INDEXER_INCREMENT = 1000;

    public $table;
    public $queries = array();
    public $queryIndexer = 0;

    public function _construct()
    {
        $this->queries[$this->queryIndexer] = array();
        return;
    }

    public function reset()
    {
        
    }

    public function incrementQueryIndexer()
    {
        if (!isset($this->queries[$this->queryIndexer])) {
            $this->queries[$this->queryIndexer] = array();
        }
        if (count($this->queries[$this->queryIndexer]) >= self::QUERY_INDEXER_INCREMENT) {

            $this->queryIndexer++;
            $this->queries[$this->queryIndexer] = array();
        }
    }

    public function getIndexes($mapping)
    {
        return [];
    }

    public function beforeCollect($profile, $columns)
    {
        self::incrementQueryIndexer();
    }

    public function collect($productId, $value, $strategy, $profile)
    {

        //self::incrementQueryIndexer();
    }

    public function prepareQueries($productId, $profile)
    {
        $this->fields = array();
        self::incrementQueryIndexer();
    }

    public function updateQueries($productId, $profile)
    {
        self::incrementQueryIndexer();
    }

    public function afterCollect()
    {
        self::incrementQueryIndexer();
    }

    public function afterProcess($profile)
    {
        
    }

    public function getValue($value)
    {

        if (in_array(strtolower($value), $this->enable)) {
            return "1";
        } else if (in_array(strtolower($value), $this->disable)) {
            return "0";
        }

        return (string) $value;
    }

    public function getDropdown()
    {
        return [];
    }

    public function hasFields()
    {
        return $this->getFields();
    }

    public function getFields($fieldset = null, $model = false, $form = null)
    {

        return false;
    }

    public function addModuleIf($profile)
    {
        return false;
    }

    public function cmp($a, $b)
    {
        return ($a['frontend_label'] < $b['frontend_label']) ? -1 : 1;
    }

    public function getAttributesList($fields = array("backend_type", "frontend_input", "attribute_code", "attribute_code", "backend_model"),
            $conditions = array(
        array("nin" => array("static")),
        array("nin" => array("media_image", "gallery")),
        array("nin" => array("image_label", "thumbnail_label", "small_image_label")),
        array("nin" => array("tax_class_id", "visibility", "status", "url_key", "special_to_date")),
        array(array("nlike" => array("%price%")), array("null" => true))
    ), $and = true)
    {


        $read = Mage::getSingleton("core/resource")->getConnection("read");
        $tableEet = Mage::getSingleton("core/resource")->getTableName('eav_entity_type');
        $select = $read->select()->from($tableEet)->where('entity_type_code=\'catalog_product\'');
        $data = $read->fetchAll($select);
        $typeId = $data[0]['entity_type_id'];



        /*  Liste des  attributs disponible dans la bdd */

        $attributesList = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($typeId);
        if ($and) {
            foreach ($fields as $i => $field) {
                $attributesList->addFieldToFilter($field, $conditions[$i]);
            }
        } else {
            $attributesList->addFieldToFilter($fields, $conditions);
        }



        $data = $attributesList->addSetInfo()
                ->getData();

        usort($data, array($this, "cmp"));

        return $data;
    }

    public function getStockFields()
    {
        $read = Mage::getSingleton("core/resource")->getConnection("read");
        $table = Mage::getSingleton("core/resource")->getTableName(Mage_CatalogInventory_Model_Stock_Item::ENTITY);

        $sql = "SHOW FULL COLUMNS FROM $table";

        $r = $read->fetchAll($sql);
        $fields = array();
        $exclude = ["item_id", "product_id", "stock_id"];
//        if (Mage::helper('core')->isModuleEnabled('Wyomind_Advancedinventory')) {
//            $exclude[] = "is_in_stock";
//            $exclude[] = "qty";
//            $exclude[] = "backorders";
//            $exclude[] = "use_config_backorders";
//        }

        foreach ($r as $data) {

            if (!in_array($data['Field'], $exclude)) {
                $fields[] = [
                    'field' => $data['Field'],
                    'comment' => $data['Comment'],
                    'type' => $data['Type']
                ];
            }
        }

        return $fields;
    }

    public function createInsertOnDuplicateUpdate($table, $data)
    {
        $fields = array();
        $values = array();
        $update = array();
        foreach ($data as $field => $value) {

            $val = $this->getValue((string) $value);
            $fields[] = "`" . $field . "`";
            $values[] = $val;
            $update[] = $field . "=" . $val . "";
        }
        return "INSERT INTO `" . $table . "` (" . implode(",", $fields) . ") "
                . " VALUES (" . implode(",", $values) . ") ON DUPLICATE KEY UPDATE " . implode(",", $update) . ";";
    }

    public function _delete($table, $data)
    {

        $update = array();
        foreach ($data as $field => $value) {

            $val = $this->getValue((string) $value);

            $delete[] = $field . "=" . $val . "";
        }
        return "DELETE FROM `" . $table . "`WHERE " . implode(" AND ", $delete) . ";";
    }

}
