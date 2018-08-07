<?php

class Wyomind_Massstockupdate_Helper_Data extends Mage_Core_Helper_Abstract
{

    public $module = "massstockupdate";
    public $attributeToCollect = array();
    private $_allowedFiles = array("csv", "txt", "tmp", "xml", "CSV", "TXT", "XML");

    const TEMP_DIR = "var/tmp/";
    const UPLOAD_DIR = "var/upload/";
    const TEMP_PREFIX = "MassStockUpdate_";
    const TEMP_EXT = ".tmp";
    const LOG_FILE = "MassStockUpdate.log";
    const ADDITIONAL_ATTR = "";

    public $modules = array(
        10 => "System",
        30 => "AdvancedInventory",
        40 => "Stock",
        100 => "Ignored"
    );

    private function skipCell()
    {
        return true;
    }

    private function skipRow()
    {
        return false;
    }

    private function skip()
    {
        return $this->skipRow();
    }

    public function execPhp($script, &$cell = null, $self = null, $attributes = array())
    {

        // Restore all break lines in the php code
        $script = str_replace("__LINE_BREAK__", "\n", $script);
        // Transform any call to $this->skip, to a return $this->skipSomething() instruction 
        $script = str_replace('$this->skip', 'return $this->skip', $script);
        if (preg_match("#^<\?(php)?(.*)(\?>)?$#mi", $script)) {

            try {
                return eval("?>" . $script);
            } catch (\Throwable $e) {
                Mage::throwException($this->__("\nError in:\n %s \n\nError message:n %s \n\n", $script, $e->getMessage()));
            }
        } else {
            return $self;
        }
    }

    public function getTempFile($params)
    {
        if ($params['file_system_type'] == '1') {
            try {
                return $this->downloadFile($params);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } elseif ($params['file_system_type'] == '2') {
            try {
                return $this->loadFile($params);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } elseif ($params['file_system_type'] == '3') {
            // Dropbox
            try {
                return $this->loadDropboxFile($params);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        return $params['file_path'];
    }

    private function loadFile($params)
    {
        $md5 = md5($params["file_path"]) . '_' . date("Y-m-d_Hi");

        $tmpFile = self::TEMP_PREFIX . $md5 . self::TEMP_EXT;

        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $tmpDir = $io->getCleanPath(Mage::getBaseDir() . DS . self::TEMP_DIR);

        $io->open(array('path' => $tmpDir));

        if ($io->fileExists($tmpFile) && false) {
            return self::TEMP_DIR . $tmpFile;
        } else {
            if ($io->isWriteable($tmpDir)) {
                $config = array(
                    'timeout' => 120, //Timeout in no of seconds
                    'header' => 0,
                );

                if (1 == $params['url_authentication']) {
                    $username = $params['url_login'];
                    $password = $params['url_password'];

                    $config['userpwd'] = $username . ':' . $password;
                }

                $curl = new Varien_Http_Adapter_Curl();
                $curl->setConfig($config);
                $curl->write(Zend_Http_Client::GET, $params["file_path"], '1.0');
                $content = $curl->read();

                if (strstr(str_replace(array("\n", "\r", "\t"), '', $content), '401 Authorization Required')) {
                    throw new Exception($this->__("Credentials are required in order to access the file '" . $params["file_path"] . ". Please check your credentials (Settings > File Location)"));
                }

                if ($content === false) {
                    throw new Exception($this->__("The file '" . $params["file_path"] . "' seems to be empty."));
                }

                $curl->close();
                if (!$content) {
                    throw new Exception($this->__("The file '" . $params["file_path"] . "' cannot be fetched."));
                }

                $io->streamOpen($tmpFile, 'w');
                $io->streamWrite($content);
                $io->streamClose();

                return self::TEMP_DIR . $tmpFile;
            } else {
                $io->close();

                throw new Exception($this->__("Please make sure that " . $tmpDir . " is writable."));
            }
        }
    }

    private function loadDropboxFile($params)
    {
        if ($params['dropbox_token']) {
            $token = $params['dropbox_token'];
            $md5 = md5($params['file_path']) . '_' . date("Y-m-d_Hi");
            $tmpFile = self::TEMP_PREFIX . $md5 . self::TEMP_EXT;
            $io = new Varien_Io_File();
            $io->setAllowCreateFolders(true);
            $tmpDir = $io->getCleanPath(Mage::getBaseDir() . DS . self::TEMP_DIR);
            $io->open(array('path' => $tmpDir));

            if ($io->fileExists($tmpFile) && false) {
                return self::TEMP_DIR . $tmpFile;
            } else {
                if ($io->isWriteable($tmpDir)) {
                    $url = "https://content.dropboxapi.com/2/files/download";
                    $headers = array(
                        'Authorization: Bearer ' . $token,
                        'Content-Type: text/plain',
                        'Dropbox-API-Arg: ' . json_encode(array('path' => $params['file_path']))
                    );

                    $config = array(
                        'timeout' => 120, //Timeout in no of seconds
                        'header' => 0,
                    );

                    $curl = new Varien_Http_Adapter_Curl();
                    $curl->setConfig($config);
                    $curl->write(Zend_Http_Client::POST, $url, '1.0', $headers);
                    $content = $curl->read();

                    if (strstr(str_replace(array("\n", "\r", "\t"), '', $content), 'invalid_access_token')) {
                        throw new Exception($this->__("The token is invalid. It can be generated from your Dropbox account https://www.dropbox.com/developers/apps"));
                    }

                    if ($content === false) {
                        throw new Exception($this->__("The file '" . $params['file_path'] . "' seems to be empty."));
                    }

                    $curl->close();

                    if (!$content) {
                        throw new Exception($this->__("The file '" . $params['file_path'] . "' cannot be fetched."));
                    }

                    $io->streamOpen($tmpFile, 'w');
                    $io->streamWrite($content);
                    $io->streamClose();

                    return self::TEMP_DIR . $tmpFile;
                } else {
                    $io->close();

                    throw new Exception($this->__("Please make sure that " . $tmpDir . " is writable."));
                }
            }
        } else {
            throw new Exception($this->__("The access token is required."));
        }
    }

    private function downloadFile($params)
    {
        try {
            $ftp = $this->getFtpConnection($params);

            if ($ftp->cd($params["ftp_dir"])) {
                $md5 = md5($params["ftp_host"] . $params["ftp_dir"] . $params["file_path"]) . '_' . date("Y-m-d_Hi");

                $tmpFile = self::TEMP_PREFIX . $md5 . self::TEMP_EXT;

                $io = new Varien_Io_File();
                $io->setAllowCreateFolders(true);
                $tmpDir = $io->getCleanPath(Mage::getBaseDir() . DS . self::TEMP_DIR);

                $io->open(array('path' => $tmpDir));

                if ($io->fileExists($tmpFile) && false) {
                    return self::TEMP_DIR . $tmpFile;
                } else {
                    if ($io->isWriteable($tmpDir)) {

                        $content = $ftp->read($params["ftp_dir"] . $params["file_path"], self::TEMP_DIR . $tmpFile);
                        $io->close();
                        $ftp->close();
                        if (!$content) {
                            throw new Exception($this->__("The file '" . $params["ftp_dir"] . $params["file_path"] . "' cannot be fetched."));
                        }
                        return self::TEMP_DIR . $tmpFile;
                    } else {
                        $io->close();
                        $ftp->close();
                        throw new Exception($this->__("Please make sure that " . $tmpDir . " is writable."));
                    }
                }
            } else {

                $ftp->close();
                throw new Exception($this->__("Cannot access '" . $params["ftp_dir"] . "' on this server."));
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getFtpConnection($params)
    {
        if ($params["use_sftp"] == "1") {
            $ftp = new Varien_Io_Sftp();
            $ftp->open(
                    array(
                        'host' => $params["ftp_host"],
                        'user' => $params["ftp_login"], //ftp
                        'username' => $params["ftp_login"], //sftp
                        'password' => $params["ftp_password"],
                        'timeout' => '120',
                        'path' => $params["ftp_dir"],
                        'passive' => !($params["ftp_active"])
                    )
            );
        } else {
            $ftp = new Varien_Io_Ftp();
            $host = $params["ftp_host"];
            $port = 21;
            if (strpos($params["ftp_host"], ':') !== false) {
                list($host, $port) = explode(':', $params["ftp_host"], 2);
            }
            $ftp->open(
                    array(
                        'host' => $host,
                        'port' => $port,
                        'user' => $params["ftp_login"], //ftp
                        'username' => $params["ftp_login"], //sftp
                        'password' => $params["ftp_password"],
                        'timeout' => '120',
                        'path' => $params["ftp_dir"],
                        'passive' => !($params["ftp_active"])
                    )
            );
        }
        return $ftp;
    }

    public function isValidFile($path)
    {
        $io = new Varien_Io_File();
        $io->open();
        $realPath = $io->getCleanPath(Mage::getBaseDir() . DS . $path);
        $array = explode(".", $path);
        $ext = array_pop($array);

        if ($path == '') {
            return $this->__('File path can\'t be empty.');
        } elseif (!in_array($ext, $this->_allowedFiles)) {
            return $this->__('Wrong file type. "%s" must be a csv, xml, txt file.', $realPath);
        }
//        elseif (!$io->fileExists($realPath)) {
//            return $this->__('Wrong file path. "%s" is not a file.', $realPath);
//        } elseif (!is_readable($realPath)) {
//            return $this->__('Please make sure that "%s" is readable by web-server.', $realPath);
//        }
        return true;
    }

    function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public function transformXmlToCsv($file, $params, $limit = -1)
    {
        $md5 = md5('date_' . date("Y-m-d_Hi") . "-limit_" . $limit . "-name_" . ($file));

        $tmpFile = self::TEMP_PREFIX . $md5 . self::TEMP_EXT;

        $io = new Varien_Io_File();

        $io->setAllowCreateFolders(true);
        $tmpDir = $io->getCleanPath(Mage::getBaseDir() . DS . self::TEMP_DIR);

        $io->open(array('path' => $tmpDir));

        if ($io->fileExists($tmpFile) && false) {
            return self::TEMP_DIR . $tmpFile;
        }

        $xml = new SimpleXMLElement(file_get_contents($file));

        $xml = $xml->xpath($params["xpath_to_product"]);

        if (!count($xml)) {
            Mage::throwException(Mage::helper('core')->__("No products were found."));
        }

        $products = array();
        $headers = array();
        $counter = 0;
        $csv = '';

        foreach ($xml as $index => $product) {
            $cell = array();
            // automatic XML Structure
            if ($limit != -1 && $counter > $limit) {
                break;
            }

            if (!$params["preserve_xml_column_mapping"]) {
                //use the longest headers rows
                if (count($headers) < count(array_keys((array) $product))) {
                    $headers = array_keys((array) $product);
                    $headers = array_unique($headers);
                }
                $columns = array_keys((array) $product);

                foreach ($columns as $i => $key) {

                    if (count($product->$key) === 1) {
                        $cell[$i] = (string) $product->$key;
                    } else {
                        $cell[$i] = "";
                    }
                }
            }
            // user defined XML Structure
            else {
                try {
                    if (!isset($structure)) {
                        $structure = Mage::helper('core')->jsonDecode($params["xml_column_mapping"]);
                    }
                } catch (\Exception $e) {
                    Mage::throwException(Mage::helper('core')->__("Invalid Json string for the XML structure."));
                }

                if (!count($headers)) {
                    $headers = array_keys($structure);
                }
                $product = new SimpleXMLElement($product->asXML());
                $i = 0;
                foreach ($structure as $header => $xpath) {
                    $result = $product->xpath($xpath);

                    if (isset($result[0])) {

                        $cell[$i] = $result[0]->__toString();
                    } else {
                        $cell[$i] = "";
                    }
                    $i++;
                }
            }

            $counter++;

            foreach ($cell as $key => $value) {
                $cell[$key] = '"' . trim(str_replace(array("\n", '"'), array(" ", '""'), $cell[$key])) . '"';
            }
            $csv .= implode(";", $cell) . "\n";
        }


        $io->streamOpen($tmpFile, 'w');
        $io->streamWrite(implode(";", $headers) . "\n");
        $io->streamWrite($csv);

        $io->streamClose();
        return self::TEMP_DIR . $tmpFile;
    }

    public function getRealPath($tempFile, $params, $limit = 1000)
    {
        $io = new Varien_Io_File();
        $io->open();
        $realPath = $io->getCleanPath(Mage::getBaseDir() . DS . $tempFile);

        if ($params["file_type"]) { // xml
            $convertedFile = $this->transformXmlToCsv($realPath, $params, $limit);

            return $io->getCleanPath(Mage::getBaseDir() . DS . $convertedFile);
        }
        return $realPath;
    }

    public function getSeparatorAndEnclosure($params)
    {
        if ($params["file_type"]) {
            $fileSeparator = ";";
            $fileEnclosure = "none";
            return array($fileSeparator, $fileEnclosure);
        }
        return array($params["file_separator"], $params["file_enclosure"]);
    }

    function getMappingDropdown($ignored = true)
    {
        $dropdown = array();

        /* Store views */
        $stores = array();
        $w = 0;
        $g = 0;
        $s = 0;

        $websites = Mage::app()->getWebsites();
        $stores["label"] = $this->__("Default value");
        $stores["value"] = $this->__("0");

        foreach ($websites as $website) {

            $stores["children"][$w]["label"] = $website->getName();
            $g = 0;
            $storegroups = $website->getGroupCollection();
            foreach ($storegroups as $storegroup) {
                $stores["children"][$w]["children"][$g]["label"] = $storegroup->getName();
                $s = 0;
                $storeviews = $storegroup->getStoreCollection();
                foreach ($storeviews as $storeview) {

                    $stores["children"][$w]["children"][$g]["children"][$s]["label"] = $storeview->getName();
                    $stores["children"][$w]["children"][$g]["children"][$s]["value"] = $storeview->getStoreId();
                    $s++;
                }
                $g++;
            }
            $w++;
        }

        $dropdown["storeviews"] = $stores;

        foreach ($this->modules as $module) {
            $resource = Mage::getResourceSingleton($this->module . "/" . $module);
            $options = $resource->getDropdown($this);
            $dropdown = array_merge($dropdown, $options);
        }

        return $dropdown;
    }

    /**
     * Line filter
     * @param string $parameters
     * @param int $lineNumber
     * @return boolean
     */
    public function getLineRangeCondition($parameters, $lineNumber, $cell = [])
    {
        $upTo = false;
        $range = false;
        $equal = false;
        $rangeCondition = true;


        $rtn = $this->execPhp($parameters, $cell);
        if ($rtn === FALSE || $rtn === TRUE) {
            return $rtn;
        }

        if ($parameters) {
            $parameters = explode(',', $parameters);

            foreach ($parameters as $value) {
                $value = str_replace(' ', '', $value);

                if (false !== strpos($value, '+')) {
                    if (false === $upTo) {
                        // From line - to the end (e.g 2+)
                        $upTo = $lineNumber >= $value;
                    }
                } elseif (false !== strpos($value, '-')) {
                    if (false === $range) {
                        // From - To line (e.g 15-20)
                        $fromTo = explode('-', $value);

                        $from = $lineNumber >= $fromTo[0];
                        $to = $lineNumber <= $fromTo[1];

                        $range = $from && $to;
                    }
                } else {
                    if (false === $equal) {
                        // One line
                        $equal = $lineNumber == $value;
                    }
                }
            }

            $rangeCondition = $equal || $range || $upTo;
        }

        return $rangeCondition;
    }

    function getLogFile()
    {
        return self::LOG_FILE;
    }

}
