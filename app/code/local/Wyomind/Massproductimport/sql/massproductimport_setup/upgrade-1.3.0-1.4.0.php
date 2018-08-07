<?php

$updater = $this;
$updater->startSetup();



$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `preserve_xml_column_mapping` INT(1) DEFAULT 0");
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `xml_column_mapping` TEXT");
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `create_configurable_onthefly` INT(1) DEFAULT 0");
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `sql` INT(1) DEFAULT 0");
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `sql_file` VARCHAR(255) DEFAULT 'file.sql'");
$updater->run("ALTER TABLE {$this->getTable('massproductimport_profile')} ADD `sql_path` VARCHAR(255) DEFAULT 'var/sql' ");

$updater->endSetup();
