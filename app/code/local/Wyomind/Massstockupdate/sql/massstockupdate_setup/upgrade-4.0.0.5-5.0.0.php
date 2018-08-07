<?php

$installer = $this;

$installer->startSetup();

$connection = $installer->getConnection();
$profileTable = $installer->getTable('massstockupdate_profile');

if ($connection->tableColumnExists($profileTable, 'profile_method') === false) {
    $connection->addColumn($profileTable, 'profile_method', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'length'    => 1,
        'nullable'  => false,
        'default'   => '0',
        'comment'   => 'Profile method'
    ));
}

if ($connection->tableColumnExists($profileTable, 'preserve_xml_column_mapping') === false) {
    $connection->addColumn($profileTable, 'preserve_xml_column_mapping', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'length'    => 1,
        'nullable'  => false,
        'default'   => '0',
        'comment'   => 'Preserve the XML structure'
    ));
}
    
if ($connection->tableColumnExists($profileTable, 'xml_column_mapping') === false) {
    $connection->addColumn($profileTable, 'xml_column_mapping', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'XML column mapping'
    ));
}

if ($connection->tableColumnExists($profileTable, 'sql') === false) {
    $connection->addColumn($profileTable, 'sql', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'length'    => 1,
        'nullable'  => false,
        'default'   => '0',
        'comment'   => 'SQL mode'
    ));
}

if ($connection->tableColumnExists($profileTable, 'sql_path') === false) {
    $connection->addColumn($profileTable, 'sql_path', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => true,
        'default'   => 'var/sql/',
        'comment'   => 'SQL file path'
    ));
}

if ($connection->tableColumnExists($profileTable, 'sql_file') === false) {
    $connection->addColumn($profileTable, 'sql_file', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => true,
        'default'   => 'update.sql',
        'comment'   => 'SQL file name'
    ));
}

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

if ($connection->tableColumnExists($profileTable, 'line_filter') === false) {
    $connection->addColumn($profileTable, 'line_filter', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => true,
        'comment'   => 'Line filter'
    ));
}

if ($connection->tableColumnExists($profileTable, 'has_header') === false) {
    $connection->addColumn($profileTable, 'has_header', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'length'    => 1,
        'nullable'  => false,
        'default'   => 1,
        'comment'   => 'Is the file has a header'
    ));
}

$installer->endSetup();