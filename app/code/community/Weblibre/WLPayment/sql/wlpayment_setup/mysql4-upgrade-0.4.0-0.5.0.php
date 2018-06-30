<?php

$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('wlpayment')} 
ADD `parcelas2` int(2) NOT NULL default 1 after `transMensagem`,
ADD `transId2` varchar(30) NOT NULL default 0 after `parcelas2`,
ADD `transResposta2` int(4) NOT NULL default 0 after `transId2`,
ADD `transValor2` decimal(10,2) NOT NULL default 0 after `transResposta2`,
ADD `transTipo2` int(2) NOT NULL default 0 after `transValor2`,
ADD `tipoPagto2` varchar(2) NOT NULL default '' after `transTipo2`,
ADD `transMensagem2` varchar(128) NOT NULL default '' after `tipoPagto2`
");

$installer->endSetup(); 