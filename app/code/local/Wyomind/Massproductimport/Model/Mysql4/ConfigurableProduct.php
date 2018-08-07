<?php

class Wyomind_Massproductimport_Model_Mysql4_ConfigurableProduct extends Wyomind_Massstockupdate_Model_Mysql4_Abstract
{

    const ITEM_SEPARATOR = ",";


    public function _construct()
    {


        $this->tableCpr = Mage::getSingleton("core/resource")->getTableName("catalog_product_relation");
        $this->tableCpsl = Mage::getSingleton("core/resource")->getTableName("catalog_product_super_link");
        $this->tableCpe = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity");
   

        parent::_construct();
    }

    public function reset()
    {
        $this->fields = array();
    }

    public function beforeCollect($profile, $columns)
    {
       
        $this->removeQueries = array();
    }

    function collect($productId, $value, $strategy, $profile)
    {


        list($field) = $strategy['option'];
        switch ($field) {

          
            case "parentSku":
                if ($value != "") {
                    $data = array(
                        "parent_id" => "(SELECT entity_id FROM `$this->tableCpe` WHERE sku='$value' LIMIT 1)",
                        "child_id" => $productId,
                    );
                    $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->tableCpr, $data);

                    $data = array(
                        "product_id" => $productId,
                        "parent_id" => "(SELECT entity_id FROM `$this->tableCpe` WHERE sku='$value' LIMIT 1)",
                    );
                    $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->tableCpsl, $data);



                    $this->fields["sku"] = $value;
                }
                return;
                break;
            case "childrenSkus":

                $values = explode(self::ITEM_SEPARATOR, $value);
                foreach ($values as $value) {
                    if ($value != "") {
                        $data = array(
                            "parent_id" => $productId,
                            "child_id" => "(SELECT entity_id FROM `$this->tableCpe` WHERE sku='$value' LIMIT 1)",
                        );
                        $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->tableCpr, $data);
                        $data = array(
                            "product_id" => "(SELECT entity_id FROM `$this->tableCpe` WHERE sku='$value' LIMIT 1)",
                            "parent_id" => $productId,
                        );
                        $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->tableCpsl, $data);
                    }
                }
                return;
                break;
                list($field) = $strategy['option'];
        }
        parent::collect($productId, $value, $strategy, $profile);
    }

    public function getDropdown()
    {
        $dropdown = array();

        $i = 0;
        $dropdown['Configurable Products'][$i]['label'] = __("Parent SKU");
        $dropdown['Configurable Products'][$i]["id"] = "ConfigurableProduct/parentSku";
        $dropdown['Configurable Products'][$i]['style'] = "configurable-product-parent";
        $dropdown['Configurable Products'][$i]['type'] = "Parent product SKU";
        $dropdown['Configurable Products'][$i]['value'] = "Sku ABC";

        $i++;
        $dropdown['Configurable Products'][$i]['label'] = __("Children SKUs");
        $dropdown['Configurable Products'][$i]["id"] = "ConfigurableProduct/childrenSkus";
        $dropdown['Configurable Products'][$i]['style'] = "configurable-product-children";
        $dropdown['Configurable Products'][$i]['type'] = "List of children product SKU's separated by " . self::ITEM_SEPARATOR;
        $dropdown['Configurable Products'][$i]['value'] = "Sku ABC" . self::ITEM_SEPARATOR . " Sku XYZ" . self::ITEM_SEPARATOR . "...";

        $i++;
        $dropdown['Configurable Products'][$i]['label'] = __("Configurable Attributes");
        $dropdown['Configurable Products'][$i]["id"] = "ConfigurableProductsSystem/attributes";
        $dropdown['Configurable Products'][$i]['style'] = "configurable-product";
        $dropdown['Configurable Products'][$i]['type'] = "List of attribute code separated by " . self::ITEM_SEPARATOR;
        $dropdown['Configurable Products'][$i]['value'] = "color" . self::ITEM_SEPARATOR . " size" . self::ITEM_SEPARATOR . "...";


        return $dropdown;
    }

    function getFields($fieldset = null, $model = false, $form = false)
    {
        if ($fieldset == null) {
            return true;
        }


        $fieldset->addField('create_configurable_onthefly', 'select', array(
            'label' => __('Create parent of configurable products on the fly'),
            'name' => 'create_configurable_onthefly',
            'value' => $model->getCreateConfigurableOnthefly(),
            'id' => 'create_configurable_onthefly',
            'required' => true,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => __('no')
                ),
                array(
                    'value' => 1,
                    'label' => __('yes')
                )
            ),
            "note" => "<script> 
                
                   $('create_configurable_onthefly').observe('change',function(){updateCreateConfigurableOnthefly()});
                   document.observe('dom:loaded',function(){updateCreateConfigurableOnthefly()});
                   function updateCreateConfigurableOnthefly(){
             
                        if($('create_configurable_onthefly').value==0){
                     
                            $$('.configurable-product').each(function(group){
                                group.ancestors()[0].setStyle({'display':'none'})
                            })
                            
                        }
                        else{
                            $$('.configurable-product').each(function(group){
                                group.ancestors()[0].setStyle({'display':''})
                            })
                        }
                    }
               
                
                </script>"
        ));
    }

}
