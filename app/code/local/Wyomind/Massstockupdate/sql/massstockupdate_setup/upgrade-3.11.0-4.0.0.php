<?php

$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE {$this->getTable('massstockupdate_import')}  RENAME {$this->getTable('massstockupdate_profile')} ");

$installer->run("ALTER TABLE {$this->getTable('massstockupdate_profile')} ADD `default_values` text ");
$installer->run("ALTER TABLE {$this->getTable('massstockupdate_profile')} DROP `auto_set_total`");


 



$installer->endSetup();
