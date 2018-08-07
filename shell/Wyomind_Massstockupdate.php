<?php

/**
 * @category    Wyomind
 * @package     Wyomind_massstockupdate
 * @version     5.0.0
 * @copyright   Copyright (c) 2016 Wyomind (https://www.wyomind.com/)
 */
require_once 'abstract.php';

require_once('WyomindMassStockUpdateShell.php');

$shell = new Wyomind_Massstockupdate_Shell();
$shell->run();
