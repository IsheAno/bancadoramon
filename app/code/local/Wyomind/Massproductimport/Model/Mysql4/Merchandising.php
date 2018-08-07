<?php

class Wyomind_Massproductimport_Model_Mysql4_Merchandising extends Wyomind_Massproductimport_Model_Mysql4_Attribute
{

    const FIELD_SEPARATOR = "|";

    public function _construct()
    {
        $this->tableCpe = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity");
        $this->tableCplt = Mage::getSingleton("core/resource")->getTableName("catalog_product_link_type");
        $this->tableCpl = Mage::getSingleton("core/resource")->getTableName("catalog_product_link");
        parent::_construct();
    }

    public function collect($productId, $value, $strategy, $profile)
    {

        list($entityType) = $strategy['option'];
        switch ($entityType) {
            case 'relation':
            case 'cross_sell':
            case 'up_sell':
                $entityTypeIsValid = true;
                break;
            default:
                $entityTypeIsValid = false;
        }
        if ($entityTypeIsValid && empty($value) == false) {
            $skus = explode(self::FIELD_SEPARATOR, $value);
            // Delete old data
            $this->queries[$this->queryIndexer][] = "DELETE FROM `" . $this->tableCpl . "` WHERE product_id=" . $productId . " AND link_type_id = (SELECT link_type_id FROM `" . $this->tableCplt . "` WHERE code = '" . $entityType . "')";
            // Insert new data
            foreach ($skus as $sku) {
                $this->queries[$this->queryIndexer][] = "INSERT INTO " . $this->tableCpl . " (product_id, linked_product_id,link_type_id) "
                        . " VALUES ($productId, (SELECT entity_id FROM `" . $this->tableCpe . "` WHERE sku = '" . $sku . "'), (SELECT link_type_id FROM `" . $this->tableCplt . "` WHERE code = '" . $entityType . "'))\n ";
            }
        }
        parent::collect($productId, $value, $strategy, $profile);
    }

    public function getDropdown()
    {

        $i = 0;
        $dropdown = [];
        $dropdown['Merchandising'][$i]['label'] = __("Related products");
        $dropdown['Merchandising'][$i]["id"] = "Merchandising/relation";
        $dropdown['Merchandising'][$i]['style'] = "merchandising";
        $dropdown['Merchandising'][$i]['type'] = "List of related product SKU's separated by " . self::FIELD_SEPARATOR;
        $dropdown['Merchandising'][$i]['value'] = "Sku ABC " . self::FIELD_SEPARATOR . " Sku XYZ " . self::FIELD_SEPARATOR . "...";
        $i++;
        $dropdown['Merchandising'][$i]['label'] = __("Cross sell");
        $dropdown['Merchandising'][$i]["id"] = "Merchandising/cross_sell";
        $dropdown['Merchandising'][$i]['style'] = "merchandising";
        $dropdown['Merchandising'][$i]['type'] = "List of related product SKU's separated by " . self::FIELD_SEPARATOR;
        $dropdown['Merchandising'][$i]['value'] = "Sku ABC " . self::FIELD_SEPARATOR . " Sku XYZ " . self::FIELD_SEPARATOR . "...";
        $i++;
        $dropdown['Merchandising'][$i]['label'] = __("Up sell");
        $dropdown['Merchandising'][$i]["id"] = "Merchandising/up_sell";
        $dropdown['Merchandising'][$i]['style'] = "merchandising";
        $dropdown['Merchandising'][$i]['type'] = "List of related product SKU's separated by " . self::FIELD_SEPARATOR;
        $dropdown['Merchandising'][$i]['value'] = "Sku ABC " . self::FIELD_SEPARATOR . " Sku XYZ " . self::FIELD_SEPARATOR . "...";

        return $dropdown;
    }

}
