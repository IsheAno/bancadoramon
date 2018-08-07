<?php

class Wyomind_Massproductimport_Model_Mysql4_Category extends Wyomind_Massstockupdate_Model_Mysql4_Abstract
{

    public $_mapping = array();

    const CATEGORY_PATH_SEPARATOR = ",";
    const CATEGORY_LEVEL_SEPARATOR = "/";
    

    protected $_attributes = array(
        "name" => array(
            "type" => "varchar"
        ),
        "is_active" => array(
            "type" => "int"
        ),
        "include_in_menu" => array(
            "type" => "int"
        ),
        "display_mode" => array(
            "default" => "PRODUCT",
            "type" => "varchar"
        ),
        "is_anchor" => array(
            "default" => 1,
            "type" => "int"
        ),
        "url_key" => array(
            "type" => "varchar"
        ),
//        "url_path" => array(
//            "type" => "varchar"
//        ),
    );
    protected $_categoryNames = array();
    protected $_categoryPaths = array();
    protected $_categoryLevels = array();
    protected $_categoryIds = array();
    protected $_categoryRegistry = array();
    protected $_filterManager = null;
    protected $_rootCategoryId;

    public function _construct()
    {
        $this->table = Mage::getSingleton("core/resource")->getTableName("catalog_category_product");
        $this->tableEntity = Mage::getSingleton("core/resource")->getTableName("catalog_category_entity");

        $this->tableEntityVarchar = Mage::getSingleton("core/resource")->getTableName("catalog_category_entity_varchar");
        $this->tableEntityInt = Mage::getSingleton("core/resource")->getTableName("catalog_category_entity_int");
        $this->_rootCategoryId = Mage_Catalog_Model_Category::TREE_ROOT_ID;
    }

    public function getIndexes($mapping)
    {
        return [1 => "catalog_category_product"];
    }

    public function beforeCollect($profile, $columns)
    {
        /* get entity type id */
        $read = Mage::getSingleton("core/resource")->getConnection("read");
        $tableEet = Mage::getSingleton("core/resource")->getTableName('eav_entity_type');
        $select = $read->select()->from($tableEet)->where('entity_type_code=\'catalog_category\'');
        $data = $read->fetchAll($select);
        $this->categoryEntityTypeId = $data[0]['entity_type_id'];



        foreach ($this->_attributes as $key => $attribute) {
            /* get name attribute id */
            $tableEava = Mage::getSingleton("core/resource")->getTableName('eav_attribute');
            $select = $read->select()->from($tableEava)->where("attribute_code='$key' AND entity_type_id=" . $this->categoryEntityTypeId);
            $data = $read->fetchAll($select);
            $var = $key . "_id";
            $this->$var = $data[0]['attribute_id'];
        }


        $this->_categoryNames = $this->getCategories(false, false, "name");

        $this->_categoryData = $this->getCategories(false, false);

        $this->_increment = $this->getNextAutoincrement($read);

        parent::beforeCollect($profile, $columns);
    }

    public function collect($productId, $value, $strategy, $profile)
    {

        $parentId = $profile->getCategoryParentId();
        $isActive = $profile->getCategoryIsActive();
        $includeInMenu = $profile->getCategoryIncludeInMenu();

        if (trim($value) != "") {

            $this->queries[$this->queryIndexer][] = "DELETE FROM `" . $this->table . "` WHERE product_id=" . $productId . ";";

            $values = explode(self::CATEGORY_PATH_SEPARATOR, $value);
            $values = array_unique($values);
            if ($profile->getTreeDetection() == false) {
                foreach ($values as $value) {
                    if (is_integer((int) $value) && (int) $value > 0) {
                        if (!isset($this->_categoryData["cat-" . $value])) {
                            //Invalid category Id
                            return;
                        } else {
                            $categoryId = $value;
                        }
                    }
                    // check the label to find the category id;
                    elseif (isset($this->_categoryNames[strtolower(trim($value))])) {
                        $categoryId = $this->_categoryNames[strtolower(trim($value))];
                    }
                    // create a new category
                    else {
                        // the category doesn't exist yet	
                        if ($profile->getCreateCategoryOnthefly()) {
                            if (!isset($this->_categoryRegistry[md5(strtolower(trim($value)))])) {
                                $parentPath = $this->_categoryData["cat-" . $parentId]["path"];
                                $parentLevel = $this->_categoryData["cat-" . $parentId]["level"];
                                $this->createCategory($parentId, $parentPath, $parentLevel, $value, $isActive, $includeInMenu);
                                $this->_categoryRegistry[md5(strtolower(trim($value)))] = true;
                                
                            }
                            $categoryId = "(SELECT e.entity_id FROM $this->tableEntity AS e INNER JOIN $this->tableEntityVarchar AS ev ON e.entity_id=ev.entity_id AND attribute_id=$this->name_id AND store_id=0 AND value='" . str_replace("'", "''", $value) . "' LIMIT 1)";
                        } else {

                            $categoryId = 0;
                        }
                    }
                    if ($categoryId) {
                        $this->queries[$this->queryIndexer][] = "INSERT INTO `" . $this->table . "` (category_id,product_id,position) values(" . $categoryId . " , " . $productId . ", '0');";
                    }
                }
            } else {
                foreach ($values as $value) {
                    // Tree detection
                    $names = explode(self::CATEGORY_LEVEL_SEPARATOR, $value);

                    $categoriesFound = array();
                    if (count($names)) {

                        $path = $this->_rootCategoryId;
                        $parentId = $this->_rootCategoryId;
                        foreach ($names as $name) {
                            $name = trim($name);

                            // get entity_id
                            $categoryId = $this->getCategoryIdByName($name, $path);

                            if (!$categoryId) {

                                if (!$profile->getCreateCategoryOnthefly()) {
                                    break 2;
                                } else {
                                    // create new category
                                    $parentPath = $this->_categoryData["cat-" . $parentId]["path"];
                                    $parentLevel = $this->_categoryData["cat-" . $parentId]["level"];
                                    $this->createCategory($parentId, $parentPath, $parentLevel, $name, $isActive, $includeInMenu);

                                    // store the new category data
                                    $this->_categoryData["cat-" . $this->_increment] = array(
                                        'id' => $this->_increment,
                                        "path" => $parentPath . "/" . $this->_increment,
                                        "name" => $name,
                                        "level" => $parentLevel + 1,
                                    );


                                    $categoryId = $this->_increment;
                                    $this->_increment++;
                                }
                            }
                            $path.= "/" . $categoryId;
                            $parentId = $categoryId;
                        }

                        $this->queries[$this->queryIndexer][] = "INSERT INTO `" . $this->table . "` (category_id,product_id,position) values(" . $categoryId . " , " . $productId . ", '0');";
                    }
                }
            }

            parent::collect($productId, $value, $strategy, $profile);
        }
    }

    private function createCategory($parentId, $parentPath, $parentLevel, $value, $isActive,
            $includeInMenu)
    {
        $this->queries[$this->queryIndexer][] = "INSERT INTO `$this->tableEntity` (entity_id,entity_type_id,attribute_set_id,parent_id,created_at,updated_at,path,position,children_count)"
                . "VALUES(NULL,$this->categoryEntityTypeId,0,$parentId,'" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s") . "','',0,0);";
        $this->queries[$this->queryIndexer][] = "SET @category_id=LAST_INSERT_ID();";
        $this->queries[$this->queryIndexer][] = "UPDATE  `$this->tableEntity` SET path=CONCAT('$parentPath','/',@category_id), level=$parentLevel+1 WHERE entity_id=@category_id;";

        foreach ($this->_attributes as $key => $attribute) {
            $var = $key . "_id";
            switch ($key) {
                case "name":
                    $val = str_replace("'", "''", $value);
                    break;
                case "is_active":
                    $val = $isActive;
                    break;
                case "include_in_menu":
                    $val = $includeInMenu;
                    break;
                case "url_key":
                    $val = ($value);
                    break;
                case "url_path":
                    $val = "url_path";
                    break;
                default :
                    $val = $attribute["default"];
            }

            $table = "tableEntity" . ucfirst($attribute["type"]);
            $this->queries[$this->queryIndexer][] = "INSERT INTO `" . $this->$table . "`  (attribute_id,store_id,entity_id,entity_type_id,value) VALUES (" . $this->$var . ",0,@category_id,$this->categoryEntityTypeId,'$val');";
        }
    }

    public function afterCollect()
    {

        $this->queries[$this->queryIndexer][] = "UPDATE " . $this->tableEntity . " SET children_count = (
                            SELECT COUNT(*) FROM (SELECT * FROM " . $this->tableEntity . ") AS category_alias WHERE path LIKE CONCAT(" . $this->tableEntity . ".path, '/%')
                            );";

        return parent::afterCollect();
    }

    public function getDropdown()
    {
        $dropdown = array();
        /* OTHER MAPPING */
        $i = 0;
        $dropdown['Category'][$i]['label'] = Mage::helper("massproductimport")->__("Category mapping");
        $dropdown['Category'][$i]['id'] = "Category/mapping";
        $dropdown['Category'][$i]['style'] = "category";
        $dropdown['Category'][$i]['type'] = "List of category names (case sensitive) or category ids separated by" . self::CATEGORY_PATH_SEPARATOR;
        $dropdown['Category'][$i]['value'] = "Category A" . self::CATEGORY_PATH_SEPARATOR . " Category B" . self::CATEGORY_PATH_SEPARATOR . "...";
        return $dropdown;
    }

    function getFields($fieldset = null, $model = null, $form = null)
    {
        if ($fieldset == null) {
            return true;
        }
        $fieldset->addField('create_category_onthefly', 'select', array(
            'name' => 'create_category_onthefly',
            'label' => __('Create categories on the fly'),
            "required" => true,
            'value' => $model->getCreateCategoryOnthefly(),
            'values' => array(
                array(
                    'value' => 0,
                    'label' => 'No'
                ),
                array(
                    'value' => 1,
                    'label' => 'Yes'
                ),
            ),
            'note' => "",
        ));

        $fieldset->addField('category_is_active', 'select', array(
            'name' => 'category_is_active',
            'label' => __('New categories active by default'),
            "required" => true,
            'value' => $model->getCategoryIsActive(),
            'values' => array(
                array(
                    'value' => 0,
                    'label' => 'No'
                ),
                array(
                    'value' => 1,
                    'label' => 'Yes'
                ),
            ),
            'note' => "",
        ));

        $fieldset->addField('category_include_in_menu', 'select', array(
            'name' => 'category_include_in_menu',
            'label' => __('New categories included in menu by default'),
            "required" => true,
            'value' => $model->getCategoryIncludeInMenu(),
            'values' => array(
                array(
                    'value' => 0,
                    'label' => 'No'
                ),
                array(
                    'value' => 1,
                    'label' => 'Yes'
                ),
            ),
            'note' => "",
        ));

        $fieldset->addField('category_parent_id', 'select', array(
            'name' => 'category_parent_id',
            'label' => __('New categories are children of'),
            "required" => true,
            'value' => $model->getCategoryParentId(),
            'values' => $this->getCategories(),
            'note' => "",
        ));
        
        $fieldset->addField('tree_detection', 'select', array(
            'name' => 'tree_detection',
            'label' => __('Category tree auto-detection'),
            "required" => true,
            'value' => $model->getTreeDetection(),
            'values' => array(
                array(
                    'value' => 0,
                    'label' => 'No'
                ),
                array(
                    'value' => 1,
                    'label' => 'Yes'
                ),
            ),
            'note' => "Category levels must be separated by slashes ( / )
            <script> 
                $('tree_detection').observe('change',function(){updateCategoryParentId()});
                $('create_category_onthefly').observe('change',function(){updateCategoryParentId()});
                document.observe('dom:loaded',function(){updateCategoryParentId()});
                function updateCategoryParentId(){
                    if($('tree_detection').value==0 && $('create_category_onthefly').value!=0){
                        $('category_parent_id').ancestors()[1].setStyle({'display':''})
                    }
                    else{
                        $('category_parent_id').ancestors()[1].setStyle({'display':'none'})
                    }
                }
            </script>",
        ));

        if (version_compare(Mage::getVersion(), '1.4.0', '>')) {
            $form->setChild('form_after', $form->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                            ->addFieldMap('images_system_type', 'images_system_type')
                            ->addFieldMap('images_use_sftp', 'images_use_sftp')
                            ->addFieldMap('images_ftp_host', 'images_ftp_host')
                            ->addFieldMap('images_ftp_login', 'images_ftp_login')
                            ->addFieldMap('images_ftp_password', 'images_ftp_password')
                            ->addFieldMap('images_ftp_active', 'images_ftp_active')
                            ->addFieldDependence('images_ftp_host', 'images_system_type', 1)
                            ->addFieldDependence('images_use_sftp', 'images_system_type', 1)
                            ->addFieldDependence('images_ftp_login', 'images_system_type', 1)
                            ->addFieldDependence('images_ftp_password', 'images_system_type', 1)
                            ->addFieldDependence('images_ftp_active', 'images_system_type', 1)
                            ->addFieldDependence('images_ftp_active', 'images_use_sftp', 0)
                            ->addFieldMap('create_category_onthefly', 'create_category_onthefly')
                            ->addFieldMap('category_is_active', 'category_is_active')
                            ->addFieldMap('category_include_in_menu', 'category_include_in_menu')
                            ->addFieldMap('category_parent_id', 'category_parent_id')
                            ->addFieldDependence('category_is_active', 'create_category_onthefly', 1)
                            ->addFieldDependence('category_include_in_menu', 'create_category_onthefly', 1)
                            ->addFieldDependence('category_parent_id', 'create_category_onthefly', 1));
        }
    }

    function getCategoryIdByName($name = false, $path = false)
    {
        foreach ($this->_categoryData as $key => $categoryData) {
            $pathStartWithChunk = explode("/", $categoryData["path"]);
            array_pop($pathStartWithChunk);
            $pathStartWith = implode("/", $pathStartWithChunk);
            
            if (strtolower($categoryData["name"]) == strtolower($name) && (string) $pathStartWith === (string) $path) {
                return $categoryData["id"];
            }
        }
        return false;
    }

    public function getNextAutoincrement($read)
    {
        $entityStatus = $read->showTableStatus($this->tableEntity);

        if (empty($entityStatus['Auto_increment'])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Cannot get autoincrement value'));
        }
        return $entityStatus['Auto_increment'];
    }

    function getCategories($category = false, $dropdown = true, $field = null)
    {

        $options = array();
        if (!$category) {
            $category = Mage::getModel('catalog/category')->load(Mage_Catalog_Model_Category::TREE_ROOT_ID);
        }

        //if ($category->getLevel() > 0) {
            if ($dropdown) {
                $label = trim(str_repeat('--', $category->getLevel()) . ' ' . $category->getName());
                $options[] = array(
                    'value' => $category->getId(),
                    'label' => $label,
                );
            } else {
                if ($field == name) {
                    $method = "get" . ucwords($field);
                    $options[strtolower($category->$method())] = $category->getId();
                } else {
                    $method = "get" . ucwords($field);
                    $options["cat-" . $category->getId()] = array(
                        "id" => $category->getId(),
                        "path" => $category->getPath(),
                        "name" => $category->getName(),
                        "level" => $category->getLevel(),
                    );
                }
            }
        //}

        // TODO: check this for the magento 2 module. Previously, it was a call to getChildren, but that does not return inactive categories
        if ($category->getChildrenCategoriesWithInactive()) {
            $children = Mage::getModel('catalog/category')->getCollection();
            $children->addAttributeToSelect('name')
                    ->addFieldToFilter('parent_id', $category->getId());

            foreach ($children as $child) {

                /** @var Mage_Catalog_Model_Category $child */
                $options = array_merge($options, $this->getCategories($child, $dropdown, $field));
            }
        }

        return $options;
    }

}
