<?php

class Wyomind_Massstockupdate_Adminhtml_MassstockupdateController extends Mage_Adminhtml_Controller_Action
{

    public $module = "massstockupdate";

    protected function _initAction()
    {
        $this->_title($this->__('Mass Stock Update'));
        $this->loadLayout();
        $this->_setActiveMenu('system/convert');

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/convert/' . $this->module);
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('profile_id');
        $model = Mage::getModel($this->module . '/import')->load($id);

        if ($model->getProfileId() || $id == 0) {
            $data = $this->_getSession()->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register($this->module . '_data', $model);
            $this->_initAction();
            $this->_title($model->getProfileName());
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock($this->module . '/adminhtml_import_edit'))
                    ->_addLeft($this->getLayout()->createBlock($this->module . '/adminhtml_import_edit_tabs'));

            $this->renderLayout();
        } else {
            $this->_getSession()->addError(Mage::helper($this->module)->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
// check if data sent
        if ($data = $this->getRequest()->getPost()) {
// init model and set data
            $model = Mage::getModel($this->module . '/import');

            if ($this->getRequest()->getParam('profile_id')) {
                $model->load($this->getRequest()->getParam('profile_id'));
            }

            $model->setData($data);

// try to save it
            try {
// save the data
                $model->save();

// display success message
                $this->_getSession()->addSuccess(Mage::helper($this->module)->__('The profile has been saved.'));
// clear previously saved data from session
                $this->_getSession()->setFormData(false);

// go to grid or forward to run action
                if ($this->getRequest()->getParam('run')) {
                    $this->getRequest()->setParam('profile_id', $model->getProfileId());
                    $this->_forward('run');
                    return;
                }

                $this->getRequest()->setParam('profile_id', $model->getProfileId());
                $this->_forward('edit');
                return;
            } catch (Exception $e) {

// display error message
                $this->_getSession()->addError($e->getMessage());
// save data in session
                $this->_getSession()->setFormData($data);
// redirect to edit form
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('profile_id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
// check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('profile_id')) {
            try {
// init model and delete
                $model = Mage::getModel($this->module . '/import');
                $model->setId($id);
// init and load ordersexporttool model

                $model->load($id);

                $model->delete();
// display success message
                $this->_getSession()->addSuccess(Mage::helper($this->module)->__('The profile has been deleted.'));
// go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
// display error message
                $this->_getSession()->addError($e->getMessage());

                $this->_redirect('*/*/');
                return;
            }
        }
// display error message
        $this->_getSession()->addError(Mage::helper($this->module)->__('Unable to find the profile to delete.'));
// go to grid
        $this->_redirect('*/*/');
    }

    public function ftpAction()
    {
        try {
            $type = $this->getRequest()->getParam("type");
            $params = array();
            $params["ftp_host"] = Mage::app()->getRequest()->getParam($type . 'ftp_host');
            $params["ftp_login"] = Mage::app()->getRequest()->getParam($type . 'ftp_login');
            $params["ftp_password"] = Mage::app()->getRequest()->getParam($type . 'ftp_password');
            $params["ftp_dir"] = Mage::app()->getRequest()->getParam($type . 'ftp_dir');
            $params["use_sftp"] = Mage::app()->getRequest()->getParam($type . 'use_sftp');
            $params["ftp_active"] = Mage::app()->getRequest()->getParam($type . 'ftp_active');

            $ftp = Mage::helper($this->module)->getFtpConnection($params);

            $ftp->write(null, null);
            $ftp->close();

            die("Connection suceeded");
        } catch (Exception $e) {
            die(Mage::helper($this->module)->__("Ftp error : ") . $e->getMessage());
        }
    }

    public function skip()
    {
        return false;
    }

    public function loadLibraryAction()
    {
        $rtn['status'] = 'valid';
        $rtn['body'] = array();
        $rtn['headers'] = array("Attribute", "Type", "Example");
        $helper = Mage::helper($this->module);
        foreach ($helper->getMappingDropdown() as $name => $group) {
            if ($name == "storeviews") {
                continue;
            }

            foreach ($group as $attribute) {

                $value = isset($attribute["value"]) ? $attribute["value"] : "-";
                $rtn['body'][] = array("<b>" . $name . "</b> | " . $attribute["label"], $attribute["type"], $value);
            }
        }
        die(json_encode($rtn));
    }

    public function loadPreviewAction()
    {
        try {
            $params = (array) Mage::app()->getRequest()->getParams();
            $helper = Mage::helper($this->module);
            $tempFile = $helper->getTempFile($params);

            if (($valid = $helper->isValidFile($tempFile)) !== true) {
                die(json_encode(['status' => 'error', 'body' => $valid]));
            } else {
                $rtn['status'] = 'valid';
                $rtn['body'] = array();
                $realPath = $helper->getRealPath($tempFile, $params);

                list($fileSeparator, $fileEnclosure) = $helper->getSeparatorAndEnclosure($params);

                /* read csv file */
                $io = new Varien_Io_File();
                $io->streamOpen($realPath, 'r');

                $line = 1;
                $counter = 0;
                $nbMaxColumn = 0;


                if ($fileEnclosure == "none") {
// Default file enclosure
                    $fileEnclosure = '"';
                }
                $mapping = json_decode($params['mapping']);
                $rtn["headers"] = array();
                $rtn["headers"][] = $params["identifier_label"];
                foreach ($mapping as $column) {
                    if ($column->enabled) {
                        $rtn["headers"][] = $column->label;
                    }
                }

                while (false !== ($cell = $io->streamReadCsv($fileSeparator, $fileEnclosure)) && $counter < 1000) {
// Line filter
                    $rangeCondition = $helper->getLineRangeCondition($params['line_filter'], $line, $cell);

                    if (($rangeCondition || $line === 1)) {
//          

                        foreach ($cell as $key => $value) {
                            if (!mb_check_encoding($value, 'UTF-8')) {
                                $cell[$key] = mb_convert_encoding($value, 'UTF-8');
                            }
                        }


                        if ($line === 1 && 0 == $params['has_header'] && 0 == $params['file_type']) {
                            $rtn['body'][0] = $cell;
                        }

                        $skipped = false;

                        $rtn["body"][$line] = array();
                        try {
                            $identifier_value = Mage::Helper($this->module . "/data")->execPhp($params["identifier_script"], $cell, $cell[(int) $params["sku_offset"]]);
                        } catch (Exception $e) {
                            $rtn['status'] = "error";
                            $rtn['body'] = $helper->__("Error in scipt for Identifier :") . nl2br(htmlentities($e->getMessage()));
                            die(json_encode($rtn));
                        }
                        if ($identifier_value === FALSE) {
                            $skipped = true;
                            $identifier_value = "<i class='skipped'> " . __("skip this cell and next cells") . "</i>";
                        } else if ($identifier_value === TRUE) {
                            $identifier_value = "<i class='skipped'> " . __("skip only this cell") . "</i>";
                        }
                        $rtn["body"][$line][] = $identifier_value;
                        $cell["identifier"] = $identifier_value;

                        foreach ($mapping as $column) {
                            $self = "";
                            if (isset($column->index) && $column->index != "") {

                                $cell[$column->source] = $cell[$column->index];
                            }

                            if ($column->enabled) {
                                if ($skipped === true) {
                                    $self = "<i class='skipped'> " . __("skipped") . "</i>";
                                    $rtn["body"][$line][] = $self;
                                    continue;
                                }
                                if (isset($column->index) && $column->index != "") {
// attribute is mapped with one data source
                                    $self = $cell[$column->index];
                                } else {
// attribute is mapped with a custom value
                                    if ($column->scripting == "") {
                                        $self = $column->default;
                                    }
                                }
                                if ($column->scripting != "") {
                                    $before = $self;

                                    try {
                                        $self = Mage::Helper($this->module . "/data")->execPhp($column->scripting, $cell, $self);
                                        if ($self === FALSE) {
                                            $skipped = true;
                                            $rtn["body"][$line][] = "<i class='skipped'> " . __("skip this cell and next cells ") . "</i>";
                                            continue;
                                        } elseif ($self === TRUE) {
                                            $self = "<i class='skipped'> " . __("skip only this cell") . "</i>";
                                            $rtn["body"][$line][] = $self;
                                            continue;
                                        }
                                    } catch (Exception $e) {
                                        $rtn['status'] = "error";
                                        $rtn['body'] = $helper->__("Error in scipt for $column->label :") . nl2br(htmlentities($e->getMessage()));
                                        die(json_encode($rtn));
                                    }
                                    $after = $self;
                                    if ($before != $after) {
                                        if ($before == "") {
                                            $before = $helper->__("null");
                                        }
                                        if ($after == "") {
                                            $after = $helper->__("null");
                                        }
                                        $self = "<span class='dynamic'>" . $helper->__("Dynamic value = ") . "<i> " . $after . "</i></span>" . "<br><span class='previous'>" . $helper->__("Original value = ") . " <i>" . $before . "</i></span>";
                                    }
                                }
                                $rtn["body"][$line][] = $self;
                            }
                        }
                    }





// Check how many columns compose the line
                    $nbColumn = count($cell);

                    if ($nbColumn > 0 && $nbColumn > $nbMaxColumn) {
                        $nbMaxColumn = $nbColumn;
                    }

                    $counter++;

                    $line++;
                }
            }

            array_shift($rtn['body']);



            $io->streamClose();
//           

            die(json_encode($rtn));
        } catch (Exception $e) {
            die(json_encode(['status' => 'error', 'body' => $e->getMessage()]));
        }
    }

    public function loadfileAction()
    {
        try {
            $params = (array) Mage::app()->getRequest()->getParams();
            $helper = Mage::helper($this->module);
            $tempFile = $helper->getTempFile($params);

            if (($valid = $helper->isValidFile($tempFile)) !== true) {
                die(json_encode(['status' => 'error', 'body' => $valid]));
            } else {
                $rtn['status'] = 'valid';
                $rtn['body'] = array();
                $realPath = $helper->getRealPath($tempFile, $params);

                list($fileSeparator, $fileEnclosure) = $helper->getSeparatorAndEnclosure($params);



                /* read csv file */
                $io = new Varien_Io_File();
                $io->streamOpen($realPath, 'r');

//                $sku_offset = $params['sku_offset'];
//                $offset = $sku_offset - 1;
                $line = 1;
                $counter = 0;
                $nbMaxColumn = 0;


                if ($fileEnclosure == "none") {
// Default file enclosure
                    $fileEnclosure = '"';
                }

                while (false !== ($csvLine = $io->streamReadCsv($fileSeparator, $fileEnclosure)) && $counter < 1000) {
// Line filter
                    $rangeCondition = $helper->getLineRangeCondition($params['line_filter'], $line, $csvLine);

                    if ($rangeCondition || $line === 1) {
//                        $skus = array_splice($csvLine, $offset, 1);
//                        array_unshift($csvLine, $skus[0]);

                        foreach ($csvLine as $key => $value) {
                            if (!mb_check_encoding($value, 'UTF-8')) {
                                $csvLine[$key] = mb_convert_encoding($value, 'UTF-8');
                            }
                        }

                        if (strlen($csvLine[0]) > 50) {
                            $csvLine[0] = '<span title="' . $csvLine[0] . '">' . substr($csvLine[0], 0, 50) . '...' . '</span>';
                        }

                        if ($line === 1 && 0 == $params['has_header'] && 0 == $params['file_type']) {
                            $rtn['body'][0] = $csvLine;
                        }

                        $rtn['body'][$line] = $csvLine;


// Check how many columns compose the line
                        $nbColumn = count($csvLine);

                        if ($nbColumn > 0 && $nbColumn > $nbMaxColumn) {
                            $nbMaxColumn = $nbColumn;
                        }

                        $counter++;
                    }
                    $line++;
                }

                $headers = array_shift($rtn['body']);

                if (0 == $params['has_header'] && 0 == $params['file_type']) {
                    $headers = array();
                }

// Detect headers with missing columns
                if (count($headers) < $nbMaxColumn) {
                    for ($i = 0; $i < $nbColumn; $i++) {
                        if (false === array_key_exists($i, $headers)) {
                            $headers[$i] = 'empty header (' . $i . ')';
                        }
                    }
                }

                $io->streamClose();
            }

            $rtn['headers'] = $headers;

            if (count($headers) < 1) {
                $rtn["headers"][] = $this->__("empty");
                $rtn["body"][] = $this->__("empty");
            }

            die(json_encode($rtn));
        } catch (Exception $e) {
            die(json_encode(['status' => 'error', 'body' => $e->getMessage()]));
        }
    }

    public function runAction()
    {
        $id = $this->getRequest()->getParam('profile_id');

        $import = Mage::getModel($this->module . '/import');
        $import->setId($id);

        if ($import->load($id)) {
            try {
                $messages = $import->importProcess();

                $this->_getSession()->addSuccess(Mage::helper($this->module)->__('The profile  "%s" has been executed.', $import->getProfileName()));
                if ($messages["error"]) {
                    $this->_getSession()->addError($messages["error"]);
                }
                if ($messages["warning"]) {
                    $this->_getSession()->addWarning($messages["warning"]);
                }
                if ($messages["success"]) {
                    $this->_getSession()->addSuccess($messages["success"]);
                }

                if (Mage::getStoreConfig($this->module . "/import/report_debug")) {
                    $this->_getSession()->addSuccess(Mage::helper($this->module)->__('No data updated (debug mode)'));
                }
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_getSession()->addException($e, Mage::helper($this->module)->__('Unable to run the profile.'));
            }
        } else {
            $this->_getSession()->addError(Mage::helper($this->module)->__('Unable to find a profile to run.'));
        }

        if ($this->getRequest()->getParam('run')) {
            $this->_forward('edit', null, null, array("profile_id" => $id));
        } else {
            $this->_forward('index');
        }
    }

    public function updaterAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function uploadAction()
    {
        $this->loadLayout()->renderLayout();
    }

}
