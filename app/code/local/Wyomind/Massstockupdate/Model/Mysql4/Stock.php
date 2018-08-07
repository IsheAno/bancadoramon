<?php

class Wyomind_Massstockupdate_Model_Mysql4_Stock extends Wyomind_Massstockupdate_Model_Mysql4_Abstract
{

    protected $_backorders;
    protected $_minQty;
    public $fields;
    protected $_setStockStatus;
    public $qtyField = false;
    public $backorderField = false;

    public function _construct()
    {

        $this->table = Mage::getSingleton("core/resource")->getTableName("cataloginventory_stock_item");
        $this->_backorders = (int) Mage::getStoreConfig("cataloginventory/item_options/backorders");
        $this->_minQty = (int) Mage::getStoreConfig("cataloginventory/item_options/min_qty");
    }

    public function reset()
    {
        $this->fields = array();
        $this->qtyField = false;
    }

    public function getIndexes($mapping)
    {
        return [2 => "cataloginventory_stock"];
    }

    public function collect($productId, $value, $strategy, $profile)
    {
        $field = $strategy['option'][0];
        if ($field == "qty") {
            $this->qtyField = $value;
        }
        $this->fields[$field] = $value;


        parent::collect($productId, $value, $strategy, $profile);
    }

    public function prepareQueries($productId, $profile)
    {

        if ($profile->getAutoSetInstock()) {

            if (is_numeric($this->qtyField) || is_string($this->qtyField)) {
                $field = $this->qtyField;
            } else {
                $field = "qty";
            }

            if (is_string($this->backorderField)) {
                $backorderCondition = $this->backorderField;
            } else {
                $backorderCondition = "(backorders>0 AND use_config_backorders=0) OR (use_config_backorders=1 AND $this->_backorders>0)";
            }
            $this->fields["is_in_stock"] = " IF ($field > $this->_minQty OR $backorderCondition,1,0)";
        }

        $data = $this->fields;
        $data["product_id"] = $productId;
        $data["stock_id"] = "1";


        $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->table, $data);
        parent::prepareQueries($productId, $profile);
    }

    public function getDropdown()
    {
        $dropdown = array();
        /* STOCK MAPPING */
        $i = 0;
        $fields = $this->getStockFields();
        if (Mage::helper('core')->isModuleEnabled('Wyomind_Advancedinventory')) {
            $dropdown['Stocks'][$i]['label'] = Mage::helper($this->module)->__("Multi stock enabled");
            $dropdown['Stocks'][$i]['id'] = "AdvancedInventory/manage_local_stock";
            $dropdown['Stocks'][$i]['style'] = "stock";
            $dropdown['Stocks'][$i]['type'] = __("Enable multi stock management with Advanced Inventory");
            $dropdown['Stocks'][$i]['value'] = implode(", ", $this->enable) . " or " . implode(", ", $this->disable);
            $i++;
        }
        foreach ($fields as $field) {
            $dropdown['Stocks'][$i]['label'] = $field["comment"];
            $dropdown['Stocks'][$i]['id'] = "Stock/" . $field["field"];
            $dropdown['Stocks'][$i]['style'] = "stock";
            if (strstr($field["type"], "smallint")) {
                $type = $this->smallint;
                $values = implode(", ", $this->enable) . " or " . implode(", ", $this->disable);
            } elseif (strstr($field["type"], "decimal")) {
                $type = $this->decimal;
                $values = "";
            }
            $dropdown['Stocks'][$i]['type'] = $type;
            $dropdown['Stocks'][$i]['value'] = $values;
            $i++;
        }
        return $dropdown;
    }

    public function getFields($fieldset = null, $model = false, $form = false)
    {

        if ($fieldset == null) {
            return true;
        }
        $fieldset->addField('auto_set_instock', 'select', array(
            'name' => 'auto_set_instock',
            'value' => $model->getAutoSetInstock(),
            'label' => Mage::helper($this->module)->__('Automatic stock status update'),
            'note' => 'Stock status will be automatically updated (in stock / out of stock)',
            'options' => array(
                1 => 'yes',
                0 => 'no',
            ),
        ));

        return $fieldset;
    }

    public function addModuleIf($profile)
    {
        if ($profile->getAutoSetInstock()) {
            return "Stock";
        }
    }

}
