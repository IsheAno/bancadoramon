<?php

$updater = $this;
$updater->startSetup();



$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `product_removal` int(1) DEFAULT 0");
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `product_target` int(1) DEFAULT 0");




$updater->endSetup();
