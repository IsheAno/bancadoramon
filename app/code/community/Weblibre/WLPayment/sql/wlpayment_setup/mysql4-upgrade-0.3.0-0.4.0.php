<?php

$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('wlpayment')} 
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
ADD `dataFraude` datetime NULL after `ip`
");

$installer->endSetup(); 