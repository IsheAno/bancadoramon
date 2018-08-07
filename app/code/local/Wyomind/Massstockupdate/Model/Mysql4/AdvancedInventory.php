<?php

class Wyomind_Massstockupdate_Model_Mysql4_AdvancedInventory extends Wyomind_Massstockupdate_Model_Mysql4_Abstract
{

    public $fields;
    protected $_tableItems;
    protected $_stockIds = array();
    protected $_stockId;
    protected $_substractedStocks = array();
    protected $_sum = array();
    protected $_backorders;

    public function _construct()
    {
        $this->_init('advancedinventory/stock', 'id');
        $this->table = Mage::getSingleton("core/resource")->getTableName("advancedinventory_stock");
        $this->_tableItems = Mage::getSingleton("core/resource")->getTableName("advancedinventory_item");
        $this->_backorders = (int) Mage::getStoreConfig("cataloginventory/item_options/backorders");
    }

    public function reset()
    {
        $this->fields = array();
        $this->_sum = array();
        $this->_substractedStocks = array();
    }

    public function getIndexes($mapping)
    {
        return [3 => "cataloginventory_stock"];
    }

    public function collect($productId, $value, $strategy, $profile)
    {
        if ($strategy["option"][0] == "manage_local_stock") {

            $val = (int) $this->getValue($value);
            $data = array(
                "product_id" => $productId,
                "manage_local_stock" => $val
            );
            $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->_tableItems, $data);
        } else {
            $placeId = $strategy["option"][0];
            $field = $strategy["option"][1];
            if (!isset($this->fields[md5($productId)][$placeId])) {
                $this->fields[md5($productId)][$placeId] = array(
                    "product_id" => $productId,
                    "localstock_id" => "(SELECT id  FROM `" . $this->_tableItems . "` WHERE product_id=" . $productId . ")",
                    "place_id" => $placeId
                );
            }

//            if (!isset($this->fields[md5($productId)][$placeId]['id'])) {
//                $this->fields[md5($productId)][$placeId]["id"] = "IFNULL((SELECT `id` FROM " . $this->table . " AS a WHERE `place_id` = " . $placeId . " AND `product_id` = " . $productId . "), NULL)";
//            }
//            if (!isset($this->fields[md5($productId)][$placeId]["manage_stock"])) {
//                $this->fields[md5($productId)][$placeId]["manage_stock"] = "IFNULL((SELECT `manage_stock` FROM `" . $this->table . "` AS `a` WHERE `place_id` = " . $placeId . " AND `product_id` = " . $productId . "), 1)";
//            }
//            if (!isset($this->fields[md5($productId)][$placeId]["quantity_in_stock"])) {
//                $this->fields[md5($productId)][$placeId]["quantity_in_stock"] = "IFNULL((SELECT `quantity_in_stock` FROM `" . $this->table . "` AS `b` WHERE `place_id` = " . $placeId . " AND `product_id` = " . $productId . "), 0), ";
//            }
//            if (!isset($this->fields[md5($productId)][$placeId]["backorder_allowed"])) {
//                $this->fields[md5($productId)][$placeId]["backorder_allowed"] = "IFNULL((SELECT `backorder_allowed` FROM `" . $this->table . "` AS `b` WHERE `place_id` = " . $placeId . " AND `product_id` = " . $productId . "), 0)";
//            }
//            if (!isset($this->fields[md5($productId)][$placeId]["use_config_setting_for_backorders"])) {
//                $this->fields[md5($productId)][$placeId]["use_config_setting_for_backorders"] = "IFNULL((SELECT `use_config_setting_for_backorders` FROM `" . $this->table . "` AS `c` WHERE `place_id` = " . $placeId . " AND `product_id` = " . $productId . "), 1)";
//            }

            if ($field == "quantity_in_stock") {
                if (!isset($this->_sum[md5($productId)])) {
                    $this->_sum[md5($productId)] = 0;
                }
                $this->_sum[md5($productId)]+=$value;
            }
            $this->fields[md5($productId)][$placeId][$field] = $this->getValue($value);

            $this->_substractedStocks[$placeId] = "-IFNULL((SELECT `quantity_in_stock` FROM `" . $this->table . "` WHERE `place_id`=" . $placeId . " AND  `product_id`=" . $productId . " ),0)";
        }
        parent::collect($productId, $value, $strategy, $profile);
    }

    public function prepareQueries($productId, $profile)
    {
        foreach ($this->fields[md5($productId)] as $warehouse) {
            $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->table, $warehouse);
        }

        $totalQty = "(SELECT (IFNULL(SUM(`quantity_in_stock`),0) " . implode('', $this->_substractedStocks) . "+" . $this->_sum[md5($productId)] . ") FROM " . $this->table . " WHERE `product_id`=$productId)";
        $stock = Mage::getResourceSingleton($this->module . "/Stock");
        $stock->qtyField = $totalQty;
        $stock->backorderField = "((SELECT MAX(IF((backorder_allowed>0 AND use_config_setting_for_backorders=0) OR (use_config_setting_for_backorders=1 AND $this->_backorders>0) OR (manage_stock=0),1,0)) FROM $this->table WHERE product_id=$productId GROUP BY product_id)=1)";


        $stock->fields["qty"] = $totalQty;

        parent::prepareQueries($productId, $profile);
    }

    public function getDropdown()
    {
        $dropdown = array();
        /* Advanced Inventory */
        if (Mage::helper('core')->isModuleEnabled('Wyomind_Advancedinventory')) {
            $attributes = [
                "Qty" => "quantity_in_stock",
                "Manage stock" => "manage_stock",
                "Backorders allowed" => "backorder_allowed",
                "Use config settings for backorders" => "use_config_setting_for_backorders"
            ];

            $places = Mage::getModel('pointofsale/pointofsale')->getPlaces();
            foreach ($places as $p) {
                $i = 0;
                foreach ($attributes as $name => $field) {
                    $dropdown[$p->getName()][$i]['label'] = $p->getName() . " - " . $name;
                    $dropdown[$p->getName()][$i]['id'] = "AdvancedInventory/" . $p->getId() . "/" . $field;
                    $dropdown[$p->getName()][$i]['style'] = "advancedInventory";
                    if ($name == "quantity_in_stock") {
                        $dropdown[$p->getName()][$i]['type'] = $this->decimal;
                        $dropdown[$p->getName()][$i]['value'] = "";
                    } else {
                        $dropdown[$p->getName()][$i]['type'] = $this->smallint;
                        $dropdown[$p->getName()][$i]['value'] = implode(", ", $this->enable) . " or " . implode(", ", $this->disable);
                    }
                    $i++;
                }
            }
        }
        return $dropdown;
    }

}
