<?php

class Wyomind_Massproductimport_Model_Mysql4_Image extends Wyomind_Massstockupdate_Model_Mysql4_Abstract
{

    public $separator = ["\|", ",", ";"];

    const LABEL_SEPARATOR = "*";
    const DEST_DIR = "media/catalog/product";
    const IMPORT_DIR = "import/";

    public $imagesToMove = array();

    public function _construct()
    {
        $this->table = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity_media_gallery");
        $this->tableValue = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity_media_gallery_value");
        $this->tableEavAttr = Mage::getSingleton("core/resource")->getTableName("eav_attribute");
        $this->tableCpev = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity_varchar");
    }

    public function collect($productId, $value, $strategy, $profile)
    {
        if ($strategy["option"][0] == "gallery") {

            $this->queries[$this->queryIndexer][] = "DELETE FROM `" . $this->table . "` WHERE entity_id=" . $productId . ";";
            $images = preg_split("/" . implode("|", $this->separator) . "/", $value);


            foreach ($images as $img) {
                $part = explode(self::LABEL_SEPARATOR, $img);

                $image = trim($part[0]);

                if ($image != "") {

                    $label = isset($part[1]) ? $part[1] : null;
                    $this->queries[$this->queryIndexer][] = "INSERT INTO `" . $this->table . "` (attribute_id, entity_id,value) values(" . $strategy["option"][1] . " , " . $productId . ", '" . addslashes(self::IMPORT_DIR . basename($image)) . "');";
                    if (!in_array($image, $this->imagesToMove)) {

                        $this->imagesToMove[] = $image;
                    }
                    $valueId = "(SELECT value_id FROM `" . $this->table . "` WHERE attribute_id=" . $strategy["option"][1] . " AND entity_id=$productId  LIMIT 1)";
                    foreach ($strategy['storeviews'] as $storeview) {

                        $data = array(
                            "value_id" => $valueId,
                            "store_id" => $storeview,
                            "label" => "'" . addslashes($label) . "'"
                        );
                        $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->tableValue, $data);
                    }
                }
            }
        } else {
            list($entityType, $attributeId) = $strategy['option'];
            $table = Mage::getSingleton("core/resource")->getTableName("catalog_product_entity_" . $entityType);

            $part = explode(self::LABEL_SEPARATOR, $value);

            $image = trim($part[0]);

            if ($image != "") {
                $label = isset($part[1]) ? $part[1] : null;
                $this->queries[$this->queryIndexer][] = "INSERT INTO `" . $this->table . "` (attribute_id, entity_id,value) values((select attribute_id FROM $this->tableEavAttr WHERE attribute_code='media_gallery') , " . $productId . ", '" . addslashes(self::IMPORT_DIR . basename($image)) . "');";
                if (!in_array($image, $this->imagesToMove)) {
                    $this->imagesToMove[] = $image;
                }
                $valueId = "(SELECT value_id FROM `" . $this->table . "` WHERE attribute_id=(select attribute_id FROM $this->tableEavAttr WHERE attribute_code='media_gallery') AND entity_id=$productId  LIMIT 1)";
                foreach ($strategy['storeviews'] as $storeview) {

                    $data = array(
                        "value_id" => $valueId,
                        "store_id" => $storeview,
                        "label" => "'" . addslashes($label) . "'"
                    );
                    $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->tableValue, $data);
                }
            }
            $data = array(
                "entity_id" => $productId,
                "entity_type_id" => parent::ENTITY_TYPE_ID,
                "attribute_id" => "'" . $attributeId . "'",
                "value" => "'" . addslashes(self::IMPORT_DIR . basename($image)) . "'"
            );
            $this->queries[$this->queryIndexer][] = $this->createInsertOnDuplicateUpdate($this->tableCpev, $data);
        }
        parent::collect($productId, $value, $strategy, $profile);
    }

    public function getDropdown()
    {
        /* IMAGES MAPPING */
        $dropdown = array();
        $fields = array("backend_model");
        $conditions = array(
            array("in" =>
                array(
                    "catalog/product_attribute_backend_media",
                )
            ),
        );
        $imageList = $this->getAttributesList($fields, $conditions, false);
        $i = 0;
        foreach ($imageList as $attribute) {
            if (!empty($attribute['frontend_label'])) {
                $dropdown['Images'][$i]['label'] = $attribute['frontend_label'];
                $dropdown['Images'][$i]['id'] = "Image/gallery/" . $attribute['attribute_id'];
                $dropdown['Images'][$i]['style'] = "image storeviews-dependent";
                $dropdown['Images'][$i]['type'] = "List of image paths separated by on of the following separator:" . stripslashes(implode("  ", $this->separator));
                $dropdown['Images'][$i]['value'] = "path/to/image1.jpg" . stripslashes($this->separator[0]) . "path/to/image2.jpg" . stripslashes($this->separator[0]) . "...";
                $i++;
            }
        }

        $fields = array("frontend_input");
        $conditions = array(
            array("in" =>
                array(
                    "media_image",
                )
            ),
        );
        $imageList = $this->getAttributesList($fields, $conditions, false);

        foreach ($imageList as $attribute) {
            if (!empty($attribute['frontend_label'])) {
                $dropdown['Images'][$i]['label'] = $attribute['frontend_label'];
                $dropdown['Images'][$i]['id'] = "Image/" . $attribute['backend_type'] . "/" . $attribute['attribute_id'];
                $dropdown['Images'][$i]['style'] = "image storeviews-dependent";
                $dropdown['Images'][$i]['type'] = "Image path and optionnal label separated by " . self::LABEL_SEPARATOR;
                $dropdown['Images'][$i]['value'] = "path/to/" . $attribute['frontend_label'] . ".jpg" . self::LABEL_SEPARATOR . "Optionnal image label";
                $i++;
            }
        }

        return $dropdown;
    }

    public function afterProcess($profile)
    {

        try {
            $images = $this->imagesToMove;
            $img = 0;
            if ($profile->getImagesSystemType() == 0) {
                $io = new Varien_Io_File();
                $fromDir = $profile->getImagesFilePath();
                $toDir = self::DEST_DIR . DS . self::IMPORT_DIR;
                foreach ($images as $image) {
                    $to = $io->getCleanPath(Mage::getBaseDir() . DS . $toDir . DS . basename($image));
                    $from = $io->getCleanPath(Mage::getBaseDir() . DS . $fromDir . DS . $image);

                    $io->mkdir(substr($to, 0, strrpos($to, "/")));
                    if ($io->fileExists($from) && !$io->fileExists($to)) {
                        try {
                            if ($io->cp($from, $to)) {
                                $img++;
                            }
                        } catch (Exception $ex) {
                            
                        }
                    }
                }
                $io->close();
            } else if ($profile->getImagesSystemType() == 1) {

                $useSftp = $profile->getImagesUseSftp();
                $ftpActive = $profile->getImagesFtpIsActive();
                $ftpHost = $profile->getImagesFtpHost();
                $ftpLogin = $profile->getImagesFtpLogin();
                $ftpPassword = $profile->getImagesFtpPassword();
                $ftpDir = $profile->getImagesFilePath();


                $params = array(
                    "use_sftp" => $useSftp,
                    'host' => $ftpHost,
                    'user' => $ftpLogin, //ftp
                    'username' => $ftpLogin, //sftp
                    'password' => $ftpPassword,
                    'timeout' => '120',
                    'path' => $ftpDir,
                    'passive' => !($ftpActive)
                );
                $ftp = Mage::helper("massproductimport")->getFtpConnection($params);

                $io = new Varien_Io_File();
                $toDir = self::DEST_DIR . DS . self::IMPORT_DIR;
                foreach ($images as $image) {
                    $to = $io->getCleanPath(Mage::getBaseDir() . DS . $toDir . DS . basename($image));
                    $io->mkdir(substr($to, 0, strrpos($to, "/")));
                    if (!$io->fileExists($to)) {
                        try {
                            if (@$ftp->read($image, $to)) {

                                $img++;
                            }
                        } catch (Exception $ex) {
                            
                        }
                    }
                }

                $io->close();
                $ftp->close();
            } else {


                $io = new Varien_Io_File();

                $toDir = self::DEST_DIR . DS . self::IMPORT_DIR;
                foreach ($images as $image) {
                    $to = $io->getCleanPath(Mage::getBaseDir() . DS . $toDir . DS . basename($image));
                    $from = $io->getCleanPath($image);

                    $io->mkdir(substr($to, 0, strrpos($to, "/")));
                    try {
                        if (strstr($from, "@")) {
                            preg_match("#(?<protocol>http(s)?):\/\/(?<login>.*):(?<password>.*)@(?<path>.*)#", $from, $matches);

                            $config['userpwd'] = $matches["login"] . ':' . $matches["password"];
                            $config ['header'] = 0;
                            $curl = new Varien_Http_Adapter_Curl();
                            $curl->setConfig($config);
                            $curl->write(Zend_Http_Client::GET, $matches["path"], '1.0');
                            $from = $curl->read();

                            if (!$io->fileExists($to)) {

                                if (file_put_contents($to, $from)) {
                                    $img++;
                                }
                            }
                        } else {


                            if (!$io->fileExists($to)) {

                                if ($io->cp($from, $to)) {
                                    $img++;
                                }
                            }
                        }
                    } catch (Exception $e) {
                        
                    }
                }
                $io->close();
            }
            Mage::getSingleton("core/session")->addSuccess(Mage::helper("massproductimport")->__($img . " images have been imported"));
        } catch (Exception $e) {
            Mage::throwException(Mage::helper("massproductimport")->__("There was an error while importing the images.") . "<br>" . $e->getMessage());
        }
        parent::afterProcess($profile);
    }

    public function getFields($fieldset = null, $model = false, $form = null)
    {

        if ($fieldset == null) {
            return true;
        }

        $fieldset->addField('images_system_type', 'select', array(
            'name' => 'images_system_type',
            'value' => $model->getImagesSystemType(),
            'label' => Mage::helper('massproductimport')->__('Images location'),
            'note' => "<script>
$('images_system_type').observe('change', function(){updateSystemType()});
document.observe('dom:loaded', function(){updateSystemType();
});
function updateSystemType(){
if($('images_system_type').value==2){
$('images_file_path').ancestors()[1].setStyle({display:'none'})
}
else{
$('images_file_path').ancestors()[1].setStyle({display:''})
}
}
</script>
",
            "required" => true,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => 'Magento File System'
                ),
                array(
                    'value' => 1,
                    'label' => 'Ftp server'
                ),
                array(
                    'value' => 2,
                    'label' => 'Http server (url)'
                ),
            ),
        ));

        $fieldset->addField('images_use_sftp', 'select', array(
            'label' => Mage::helper('massproductimport')->__('Use SFTP'),
            'name' => 'images_use_sftp',
            'id' => 'images_use_sftp',
            'value' => $model->getImagesUseSftp(),
            'required' => true,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('massproductimport')->__('no')
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('massproductimport')->__('yes')
                )
            ),
        ));
        $fieldset->addField('images_ftp_active', 'select', array(
            'label' => Mage::helper('massproductimport')->__('Use active mode'),
            'name' => 'images_ftp_active',
            'id' => 'images_ftp_active',
            'value' => $model->getImagesFtpActive(),
            'required' => true,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('massproductimport')->__('no')
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('massproductimport')->__('yes')
                )
            ),
        ));


        $fieldset->addField('images_ftp_host', 'text', array(
            'label' => Mage::helper('massproductimport')->__('Host'),
            'value' => $model->getImagesFtpHost(),
            'name' => 'images_ftp_host',
            'id' => 'images_ftp_host',
        ));

        $fieldset->addField('images_ftp_login', 'text', array(
            'label' => Mage::helper('massproductimport')->__('Login'),
            'value' => $model->getImagesFtpLogin(),
            'name' => 'images_ftp_login',
            'id' => 'images_ftp_login',
        ));
        $fieldset->addField('images_ftp_password', 'password', array(
            'label' => Mage::helper('massproductimport')->__('Password'),
            'value' => $model->getImagesFtpPassword(),
            'name' => 'images_ftp_password',
            'id' => 'images_ftp_password',
            'note' => "<a style='margin:10px; display:block;' href='javascript:massImportAndUpdate.testFtp(\"" . $form->getUrl('*/*/ftp', ["type" => "images_"]) . "\")'>Test Connection</a>",
        ));

        $fieldset->addField('images_file_path', 'text', array(
            'name' => 'images_file_path',
            'value' => $model->getImagesFilePath(),
            'label' => Mage::helper('massproductimport')->__('Path to images directory'),
        ));

        if (version_compare(Mage::getVersion(), '1.4.0', '>')) {

            $form->setChild('form_after_image', $form->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
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
                            ->addFieldDependence('images_ftp_active', 'images_use_sftp', 0));
        }
    }

}
