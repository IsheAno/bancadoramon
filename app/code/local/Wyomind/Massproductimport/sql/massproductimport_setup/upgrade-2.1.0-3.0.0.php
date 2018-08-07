<?php
$updater = $this;
$updater->startSetup();
$connection = $updater->getConnection();
$profileTable = $updater->getTable('massproductimport_profile');
if ($connection->tableColumnExists($profileTable, 'url_authentication') === false) {
    $connection->addColumn($profileTable, 'url_authentication', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'length'    => 1,
        'nullable'  => false,
        'default'   => '0',
        'comment'   => 'Use authentication for URL method'
    ));
}

if ($connection->tableColumnExists($profileTable, 'url_login') === false) {
    $connection->addColumn($profileTable, 'url_login', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 300,
        'nullable'  => true,
        'comment'   => 'URL method login'
    ));
}

if ($connection->tableColumnExists($profileTable, 'url_password') === false) {
    $connection->addColumn($profileTable, 'url_password', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 300,
        'nullable'  => true,
        'comment'   => 'URL method password'
    ));
}

if ($connection->tableColumnExists($profileTable, 'dropbox_token') === false) {
    $connection->addColumn($profileTable, 'dropbox_token', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => true,
        'comment'   => 'Dropbox access token'
    ));
}

if ($connection->tableColumnExists($profileTable, 'identifier_script') === false) {
    $connection->addColumn($profileTable, 'identifier_script', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 900,
        'nullable'  => true,
        'default'   => '',
        'comment'   => 'Script for the identifier column'
    ));
}

$updater->endSetup();