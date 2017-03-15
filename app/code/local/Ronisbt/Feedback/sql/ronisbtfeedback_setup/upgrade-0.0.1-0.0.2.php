<?php 
$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->addColumn($installer->getTable('ronisbtfeedback/feedback'), 'customer_id', array(
        'type'      =>  Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable'  => true,
        'default'   => 0,
        'comment'   => 'Customer ID',
    ));
$installer->getConnection()
    ->addColumn($installer->getTable('ronisbtfeedback/feedback'), 'user_agent', array(
        'type'      =>  Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'User agent',
    ));
$installer->getConnection()
    ->addColumn($installer->getTable('ronisbtfeedback/feedback'), 'user_ip', array(
        'type'      =>  Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'User IP',
    ));
    
$installer->endSetup();