<?php
$installer = $this;
$tableMessages = $installer->getTable('ronisbtfeedback/feedback');
$installer->startSetup();
$installer->getConnection()->dropTable($tableMessages);
$table = $installer->getConnection()
    ->newTable($tableMessages)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ))
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  => true,
        ))
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  => true,
        ))
    ->addColumn('phone', Varien_Db_Ddl_Table::TYPE_VARCHAR,null, array(
        'nullable'  =>true,
        ))
    ->addColumn('subject', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  =>true,
        ))
    ->addColumn('other_subject', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  =>true,
        ))
    ->addColumn('message', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  =>true,
        ))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
    ))    
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
    ));
$installer->getConnection()->createTable($table);
$installer->endSetup();