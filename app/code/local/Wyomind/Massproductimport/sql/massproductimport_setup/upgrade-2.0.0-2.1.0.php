<?php
$updater = $this;
$updater->startSetup();
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `webservice_params` TEXT");
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `webservice_login` VARCHAR(300) DEFAULT NULL");
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `webservice_password` VARCHAR(300) DEFAULT NULL");
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `line_filter` VARCHAR(255) DEFAULT 0");
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `has_header` INT(1) DEFAULT 0");
$updater->run("ALTER TABLE {$this->getTable('catalog_product_entity')} ADD `created_by` INT(11) DEFAULT NULL");
$updater->run("ALTER TABLE {$this->getTable('catalog_product_entity')} ADD `updated_by` INT(11) DEFAULT NULL");
$updater->endSetup();