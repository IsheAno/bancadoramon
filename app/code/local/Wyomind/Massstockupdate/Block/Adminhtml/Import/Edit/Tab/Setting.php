<?php

class Wyomind_Massstockupdate_Block_Adminhtml_Import_Edit_Tab_Setting extends Mage_Adminhtml_Block_Widget_Form
{

    public $module = "massstockupdate";

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $model = Mage::getModel($this->module . '/import');
        $model->load($this->getRequest()->getParam('profile_id'));
        $this->setForm($form);

        $fieldset = $form->addFieldset($this->module . '_settings', array('legend' => $this->__('Profile Setting')));

        (isset($_GET['debug'])) ? $type = 'text' : $type = 'hidden';

        if ($this->getRequest()->getParam('profile_id')) {
            $fieldset->addField('profile_id', $type, array(
                'name' => 'profile_id',
                'value' => $model->getProfileId()
            ));
        }

        $fieldset->addField('profile_name', 'text', array(
            'name' => 'profile_name',
            'value' => $model->getProfileName(),
            'label' => Mage::helper($this->module)->__('Profile name'),
            "required" => true
        ));

        if ($this->module == "massproductimport") {
            $visible = "select";
        } else {
            $visible = "hidden";
        }

        $fieldset->addField('profile_method', $visible, array(
            'name' => 'profile_method',
            'value' => $model->getProfileMethod(),
            'label' => Mage::helper($this->module)->__('Profile method'),
            'note' => 'In order to import new products all required attributes must be mapped with a value',
            'options' => array(
                0 => $this->__('Update products only'),
                1 => $this->__('Import new products only'),
                2 => $this->__('Update products and import new products')
            ),
        ));

        $fieldset->addField(
                'sql', 'select', [
            'name' => 'sql',
            'label' => __('SQL mode'),
            'value' => $model->getSql(),
            "required" => true,
            'values' => [
                "1" => __('Yes'),
                '0' => __('No')
            ],
            "note" => __("When SQL mode is enabled, no stocks are updated. Running the profile will only produce a SQL file. This file could be executed directly in your database manager")
                ]
        );

        $fieldset->addField(
                'sql_path', 'text', [
            'name' => 'sql_path',
            'value' => $model->getSqlPath(),
            'label' => __('SQL file path'),
            "required" => true,
            "note" => __("Path where the SQL file will be generated (relative to Magento root folder).")
                ]
        );
        $fieldset->addField(
                'sql_file', 'text', [
            'name' => 'sql_file',
            'value' => $model->getSqlFile(),
            'label' => __('SQL file name'),
            "required" => true,
            "note" => __("Name of the SQL file to generate.")
                ]
        );

        $fieldset = $form->addFieldset($this->module . '_file_location', array('legend' => $this->__('File Location')));

        $fieldset->addField('file_system_type', 'select', array(
            'name' => 'file_system_type',
            'value' => $model->getFileSystemType(),
            'label' => Mage::helper($this->module)->__('File location'),
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
                    'label' => 'Url'
                ),
                array(
                    'value' => 3,
                    'label' => 'Dropbox'
                ),
                array(
                    'value' => 4,
                    'label' => 'Web service'
                )
            ),
            'class' => "updateOnChange"
        ));

        $fieldset->addField('use_sftp', 'select', array(
            'label' => Mage::helper($this->module)->__('Use SFTP'),
            'name' => 'use_sftp',
            'id' => 'use_sftp',
            'value' => $model->getUseSftp(),
            'required' => true,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => $this->__('no')
                ),
                array(
                    'value' => 1,
                    'label' => $this->__('yes')
                )
            ),
            'class' => "updateOnChange"
        ));

        $fieldset->addField('ftp_active', 'select', array(
            'label' => Mage::helper($this->module)->__('Use active mode'),
            'name' => 'ftp_active',
            'id' => 'ftp_active',
            'value' => $model->getFtpActive(),
            'required' => true,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => $this->__('no')
                ),
                array(
                    'value' => 1,
                    'label' => $this->__('yes')
                )
            ),
            'class' => "updateOnChange"
        ));


        $fieldset->addField('ftp_host', 'text', array(
            'label' => Mage::helper($this->module)->__('Host'),
            'value' => $model->getFtpHost(),
            'name' => 'ftp_host',
            'id' => 'ftp_host',
            'class' => "updateOnChange"
        ));

        $fieldset->addField('ftp_login', 'text', array(
            'label' => Mage::helper($this->module)->__('Login'),
            'value' => $model->getFtpLogin(),
            'name' => 'ftp_login',
            'id' => 'ftp_login',
            'class' => "updateOnChange"
        ));

        $fieldset->addField('ftp_password', 'password', array(
            'label' => Mage::helper($this->module)->__('Password'),
            'value' => $model->getFtpPassword(),
            'name' => 'ftp_password',
            'id' => 'ftp_password',
            'class' => "updateOnChange"
        ));

        $fieldset->addField('ftp_dir', 'text', array(
            'label' => Mage::helper($this->module)->__('Directory'),
            'value' => $model->getFtpDir(),
            'name' => 'ftp_dir',
            'id' => 'ftp_dir',
            'note' => "<a style='margin:10px; display:block;' href='javascript:massImportAndUpdate.testFtp(\"" . $this->getUrl('*/*/ftp') . "\")'>Test Connection</a>",
            'class' => "updateOnChange"
        ));

        $fieldset->addField('file_path', 'text', array(
            'name' => 'file_path',
            'value' => $model->getFilePath(),
            'label' => Mage::helper($this->module)->__('Path to file'),
            "required" => true,
            'class' => "updateOnChange"
        ));

        $session = Mage::getSingleton('core/session');
        $SID = $session->getEncryptedSessionId();
        $fieldset->addField('file_upload', 'button', array(
            'label' => Mage::helper($this->module)->__(''),
            'name' => 'file_upload',
            "note" => " <div id='holder' class='holder'>
                            <div> Drag files from your desktop <br>txt, csv or xml files only</div>
                        </div> 

                        <progress id='uploadprogress' max='100' value='0'>0</progress>

                        <script>
                            var holder = document.getElementById('holder');
                            var progress = document.getElementById('uploadprogress');
                            var uploadUrl = '" . $this->getUrl('*/*/upload') . "?SID=" . $SID . "';
                            uploader.initialize(holder, progress,uploadUrl,'" . Mage::getSingleton('core/session')->getFormKey() . "')
                        </script>"
        ));

        // URL - Authentication fields
        $fieldset->addField('url_authentication', 'select', array(
            'label' => Mage::helper($this->module)->__('Use authentication'),
            'name' => 'url_authentication',
            'id' => 'url_authentication',
            'value' => $model->getUrlAuthentication(),
            'required' => true,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => $this->__('No')
                ),
                array(
                    'value' => 1,
                    'label' => $this->__('Yes')
                )
            ),
            'class' => "updateOnChange"
        ));

        $fieldset->addField('url_login', 'text', array(
            'label' => Mage::helper($this->module)->__('Login'),
            'value' => $model->getUrlLogin(),
            'name' => 'url_login',
            'id' => 'url_login',
            'class' => 'updateOnChange'
        ));

        $fieldset->addField('url_password', 'password', array(
            'label' => Mage::helper($this->module)->__('Password'),
            'value' => $model->getUrlPassword(),
            'name' => 'url_password',
            'id' => 'url_password',
            'class' => 'updateOnChange'
        ));

        // Dropbox token
        $fieldset->addField('dropbox_token', 'text', array(
            'label' => Mage::helper($this->module)->__('Access token'),
            'value' => $model->getDropboxToken(),
            'name' => 'dropbox_token',
            'id' => 'dropbox_token',
            'note' => __('You can generate your token from your Dropbox account https://www.dropbox.com/developers/apps'),
            'class' => 'updateOnChange'
        ));

        // Webservice
        $fieldset->addField('webservice_login', 'text', array(
            'label' => Mage::helper($this->module)->__('Login'),
            'value' => $model->getWebserviceLogin(),
            'name' => 'webservice_login',
            'id' => 'webservice_login',
            'class' => 'updateOnChange'
        ));
        $fieldset->addField('webservice_password', 'password', array(
            'label' => Mage::helper($this->module)->__('Password'),
            'value' => $model->getWebservicePassword(),
            'name' => 'webservice_password',
            'id' => 'webservice_password',
            'class' => 'updateOnChange'
        ));
        $fieldset->addField('webservice_params', 'textarea', array(
            'label' => Mage::helper($this->module)->__('Params'),
            'value' => $model->getWebserviceParams(),
            'name' => 'webservice_params',
            'id' => 'webservice_params',
            'class' => 'updateOnChange'
        ));

        $fieldset = $form->addFieldset($this->module . '_file_type', array('legend' => $this->__('File Type')));

        $fieldset->addField('file_type', 'select', array(
            'name' => 'file_type',
            'value' => $model->getFileType(),
            'label' => Mage::helper($this->module)->__('File type'),
            "required" => true,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => 'CSV'
                ),
                array(
                    'value' => 1,
                    'label' => 'XML'
                )
            ),
            'note' => "<script>
                        $('file_type').observe('change', function(){updatePreserveMapping()});
                        document.observe('dom:loaded', function(){updatePreserveMapping();
                        });
                        function updatePreserveMapping(){
                        if($('file_type').value==1 && $('preserve_xml_column_mapping').value==1){
                        $('xml_column_mapping').ancestors()[1].setStyle({display:''})
                        }
                        else{
                        $('xml_column_mapping').ancestors()[1].setStyle({display:'none'})
                        }
                        }
                        </script>",
            'class' => "updateOnChange"
        ));

        $fieldset->addField('xpath_to_product', 'text', array(
            'name' => 'xpath_to_product',
            'value' => $model->getXpathToProduct(),
            'label' => Mage::helper($this->module)->__('Xpath to products'),
            "required" => true,
            'class' => "updateOnChange"
        ));

        $fieldset->addField(
                'preserve_xml_column_mapping', 'select', [
            'label' => __('XML structure'),
            'name' => 'preserve_xml_column_mapping',
            'value' => $model->getPreserveXmlColumnMapping(),
            'id' => 'preserve_xml_column_mapping',
            'required' => true,
            'values' => [
                "1" => __('Predefined structure'),
                '0' => __('Automatic detection')
            ],
            "note" => __("The automatic detection of the XML structure fits for simple files made of a only one nesting level. "),
            'class' => "updateOnChange"
                ]
        );

        $fieldset->addField(
                'xml_column_mapping', "textarea", [
            'label' => __('Predefined XML structure'),
            'name' => 'xml_column_mapping',
            'value' => $model->getXmlColumnMapping(),
            'id' => 'xml_column_mapping',
            "note" => __("The predefined XML structure must be a valid Json string made of a key/value list that define the column names and the Xpath associated to the column"),
                    'class' => "updateOnChange"
                ]
        );

        $fieldset->addField('file_separator', 'select', array(
            'name' => 'file_separator',
            'value' => $model->getFileSeparator(),
            'label' => Mage::helper($this->module)->__('Field separator'),
            "required" => false,
            'options' => array(
                ';' => ';',
                ',' => ',',
                '|' => '|',
                "\t" => '\tab',
            ),
            'class' => "updateOnChange"
        ));

        $fieldset->addField('file_enclosure', 'select', array(
            'name' => 'file_enclosure',
            'value' => $model->getFileEnclosure(),
            'label' => Mage::helper($this->module)->__('Field enclosure'),
            "required" => true,
            'options' => array(
                "none" => 'none',
                '"' => '"',
                '\'' => '\'',
            ),
            'class' => "updateOnChange"
        ));

        $fieldset->addField('cron_setting', $type, array(
            'name' => 'cron_setting',
            'value' => $model->getCronSetting()
        ));


        $fieldset->addField('mapping', "$type", array(
            'name' => 'mapping',
            'value' => $model->getMapping()
        ));

        $fieldset->addField('sku_offset', $type, array(
            'name' => 'sku_offset',
            'value' => ($model->getSkuOffset()!="") ? $model->getSkuOffset() : 1,
        ));


        $fieldset->addField('identifier_code', $type, array(
            'name' => 'identifier_code',
            'value' => $model->getIdentifierCode(),
        ));


        $fieldset->addField('run', $type, array(
            'name' => 'run',
            'value' => ''
        ));

        if (version_compare(Mage::getVersion(), '1.4.0', '>')) {
            $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                            ->addFieldMap('file_system_type', 'file_system_type')
                            ->addFieldMap('file_type', 'file_type')
                            ->addFieldMap('file_upload', 'file_upload')
                            ->addFieldMap('file_separator', 'file_separator')
                            ->addFieldMap('file_enclosure', 'file_enclosure')
                            ->addFieldMap('xpath_to_product', 'xpath_to_product')
                            ->addFieldMap('use_sftp', 'use_sftp')
                            ->addFieldMap('ftp_host', 'ftp_host')
                            ->addFieldMap('ftp_login', 'ftp_login')
                            ->addFieldMap('ftp_password', 'ftp_password')
                            ->addFieldMap('ftp_dir', 'ftp_dir')
                            ->addFieldMap('ftp_active', 'ftp_active')
                            ->addFieldMap('xml_column_mapping', 'xml_column_mapping')
                            ->addFieldMap('sql', 'sql')
                            ->addFieldMap('sql_file', 'sql_file')
                            ->addFieldMap('sql_path', 'sql_path')
                            ->addFieldMap('url_authentication', 'url_authentication')
                            ->addFieldMap('url_login', 'url_login')
                            ->addFieldMap('url_password', 'url_password')
                            ->addFieldMap('dropbox_token', 'dropbox_token')
                            ->addFieldMap('webservice_params', 'webservice_params')
                            ->addFieldMap('webservice_login', 'webservice_login')
                            ->addFieldMap('webservice_password', 'webservice_password')
                            // SHELL MODE
                            ->addFieldDependence('sql_file', 'sql', 1)
                            ->addFieldDependence('sql_path', 'sql', 1)
                            ->addFieldMap('preserve_xml_column_mapping', 'preserve_xml_column_mapping')
                            ->addFieldDependence('file_upload', 'file_system_type', 0)
                            ->addFieldDependence('ftp_host', 'file_system_type', 1)
                            ->addFieldDependence('use_sftp', 'file_system_type', 1)
                            ->addFieldDependence('ftp_login', 'file_system_type', 1)
                            ->addFieldDependence('ftp_password', 'file_system_type', 1)
                            ->addFieldDependence('ftp_active', 'file_system_type', 1)
                            ->addFieldDependence('ftp_active', 'use_sftp', 0)
                            ->addFieldDependence('ftp_dir', 'file_system_type', 1)
                            ->addFieldDependence('url_authentication', 'file_system_type', 2)
                            ->addFieldDependence('url_login', 'file_system_type', 2)
                            ->addFieldDependence('url_login', 'url_authentication', 1)
                            ->addFieldDependence('url_password', 'file_system_type', 2)
                            ->addFieldDependence('url_password', 'url_authentication', 1)
                            ->addFieldDependence('dropbox_token', 'file_system_type', 3)
                            ->addFieldDependence('webservice_params', 'file_system_type', 4)
                            ->addFieldDependence('webservice_login', 'file_system_type', 4)
                            ->addFieldDependence('webservice_password', 'file_system_type', 4)
                            ->addFieldDependence('file_enclosure', 'file_type', 0)
                            ->addFieldDependence('file_separator', 'file_type', 0)
                            ->addFieldDependence('xpath_to_product', 'file_type', 1)
                            ->addFieldDependence('preserve_xml_column_mapping', 'file_type', 1)
                            ->addFieldDependence('xml_column_mapping', 'preserve_xml_column_mapping', 1)
                            ->addFieldMap('has_header', 'has_header')
                            ->addFieldDependence('has_header', 'file_type', 0));
        }

        return parent::_prepareForm();
    }

}
