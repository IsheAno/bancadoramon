<?php
$updater = $this;
$updater->startSetup();
$connection = $updater->getConnection();
$profileTable = $updater->getTable('massproductimport_profile');
if ($connection->tableColumnExists($profileTable, 'tree_detection') === false) {
    $connection->addColumn($profileTable, 'tree_detection', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'length'    => 1,
        'nullable'  => false,
        'default'   => '0',
        'comment'   => 'Use tree detection for categories'
    ));
}

$updater->endSetup();