<?php

$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('wlparcelamento')};
CREATE TABLE IF NOT EXISTS {$this->getTable('wlparcelamento')} (
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
    CREATE TABLE IF NOT EXISTS {$this->getTable('wlretorno')} (
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

$installer->run("
	ALTER TABLE {$this->getTable('wlpayment')} 
		MODIFY `operadoraMensagem` VARCHAR (350) NOT NULL default '';
");

$installer->endSetup(); 