<?php


$installer = $this;

$installer->startSetup();

$connection = $installer->getConnection();
$profileTable = $installer->getTable('massstockupdate_profile');

if ($connection->tableColumnExists($profileTable, 'identifier_script') === false) {
    $connection->addColumn($profileTable, 'identifier_script', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 900,
        'nullable'  => true,
        'default'   => '',
        'comment'   => 'Script for the identifier column'
    ));
}