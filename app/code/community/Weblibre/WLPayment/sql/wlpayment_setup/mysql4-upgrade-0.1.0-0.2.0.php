<?php

$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('wlpayment')};
CREATE TABLE {$this->getTable('wlpayment')} (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `parcelas` int(2) NOT NULL default 1,
	  `transId` varchar(30) NOT NULL default 0,
	  `transResposta` int(4) NOT NULL default 0,
	  `transValor` decimal(10,2) NOT NULL default 0,
	  `transTipo` int(2) NOT NULL default 0,
	  `tipoPagto` varchar(2) NOT NULL default '',
	  `transMensagem` varchar(128) NOT NULL default '',
	  `authId` varchar(27) NOT NULL default 0,
	  `authTipo` int(2) NOT NULL default 0,
	  `authValor` decimal(10) NOT NULL default 0,
	  `numeroCartao` varchar(16) NOT NULL default '',
	  `bancoEmissor` varchar(4) NOT NULL default '',
	  `autorizacaoId` bigint(10) NOT NULL default 0,
	  `numSeqUnico` bigint(12) NOT NULL default 0,
	  `numCompVenda` bigint(10) NOT NULL default 0,
	  `created_time` datetime NULL,
	  `updated_time` datetime NULL,
	  `ip` varchar(15) NOT NULL default '',
	  `statusFraude` varchar(50) NOT NULL default '',
	  `scoreFraude` int(3) NOT NULL default 0,
	  `opiniaoFraude` varchar(12) NOT NULL default '',
	  `motivoFraude` varchar(128) NOT NULL default '',
	  `free` varchar(128) NOT NULL default '',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
");

$installer->endSetup(); 