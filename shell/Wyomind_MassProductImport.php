<?php

/**
 * @category    Wyomind
 * @package     Wyomind_massproductimport
 * @version     1.4.0
 * @copyright   Copyright (c) 2016 Wyomind (https://www.wyomind.com/)
 */
require_once 'abstract.php';

require_once('WyomindMassStockUpdateShell.php');


class Wyomind_Massproductimport_Shell extends Wyomind_Massstockupdate_Shell
{

    public $module = "massproductimport";

}

$shell = new Wyomind_Massproductimport_Shell();
$shell->run();
