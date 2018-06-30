<?php

$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('wlpayment')};
CREATE TABLE {$this->getTable('wlpayment')} (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `parcelas` int(2) NOT NULL default 1,
	  `transId` varchar(30) NOT NULL default 0,
	  `transResposta` int(4) NOT NULL default 0,
	  `transValor` decimal(10,2) NOT NULL default 0,
	  `transTipo` int(2) NOT NULL default 0,
	  `tipoPagto` varchar(2) NOT NULL default '',
	  `transMensagem` varchar(128) NOT NULL default '',
      `operadora` varchar(255) NOT NULL default '',
      `operadoraMensagem` varchar(350) NOT NULL default '',
	  `parcelas2` int(2) NOT NULL default 1,
	  `transId2` varchar(30) NOT NULL default 0,
	  `transResposta2` int(4) NOT NULL default 0,
	  `transValor2` decimal(10,2) NOT NULL default 0,
	  `transTipo2` int(2) NOT NULL default 0,
	  `tipoPagto2` varchar(2) NOT NULL default '',
	  `transMensagem2` varchar(128) NOT NULL default '',
      `operadora2` varchar(255) NOT NULL default '',
      `operadoraMensagem2` varchar(255) NOT NULL default '',
	  `authId` varchar(27) NOT NULL default 0,
	  `authTipo` int(2) NOT NULL default 0,
	  `authValor` decimal(10) NOT NULL default 0,
	  `numeroCartao` varchar(16) NOT NULL default '',
	  `bancoEmissor` varchar(4) NOT NULL default '',
	  `autorizacaoId` varchar(10) NOT NULL default 0,
	  `numSeqUnico` bigint(12) NOT NULL default 0,
	  `numCompVenda` bigint(10) NOT NULL default 0,
	  `created_time` datetime NULL,
	  `updated_time` datetime NULL,
	  `ip` varchar(15) NOT NULL default '',
      `dataFraude` datetime NULL,
      `statusFraude` varchar(50) NOT NULL default '',
	  `scoreFraude` int(3) NOT NULL default 0,
	  `opiniaoFraude` varchar(12) NOT NULL default '',
	  `motivoFraude` varchar(128) NOT NULL default '',
	  `free` varchar(128) NOT NULL default '',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
");

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('wlantifraude')};
CREATE TABLE {$this->getTable('wlantifraude')} (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `login` varchar(50) NOT NULL default '',
	  `senha` varchar(20) NOT NULL default '',
	  `status` boolean NOT NULL default false,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
");


$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('wlparcelamento')};
CREATE TABLE {$this->getTable('wlparcelamento')} (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `createdData` datetime NULL,
	  `updatedData` datetime NULL,
	  `idTransacao` varchar(30) NOT NULL default '',
	  `pedido` varchar(15) NOT NULL default '',
      `vencimento` datetime NULL,
      `pagamento` datetime NULL,
	  `valor` decimal(10,2) NOT NULL default 0,
      `valorPago` decimal(10,2) NOT NULL default 0,
	  `transTipo` varchar(10) NOT NULL default '',
      `transResposta` int(4) NOT NULL default 0,
	  `transMensagem` varchar(128) NOT NULL default '',
	  `parcela` int(4) NOT NULL default 0,
      `conta` int(6) NOT NULL default 0,
	  `ip` varchar(15) NOT NULL default '',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('wlretorno')};
    CREATE TABLE {$this->getTable('wlretorno')} (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `pedido` VARCHAR( 25 ) NOT NULL ,
        `dataPedido` DATETIME NOT NULL ,
        `dataRetorno` DATETIME NOT NULL ,
        `metodo` VARCHAR( 20 ) NOT NULL ,
        `statusPedido` VARCHAR( 20 ) NOT NULL ,
        `transResposta` VARCHAR( 5 ) NOT NULL ,
        `transMensagem` VARCHAR( 255 ) NOT NULL ,
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB DEFAULT CHARSET=utf8; 
");
        
$installer->endSetup(); 