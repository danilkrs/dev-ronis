<?php 
$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->changeColumn($installer->getTable('ronisbtbanners/banners'), 'content', 'description',array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
    ));
    $installer->getConnection()->changeColumn($installer->getTable('ronisbtbanners/banners'), 'created', 'created_at',array(
        'type' => Varien_Db_Ddl_Table::TYPE_DATETIME,
        'nullable' => false,
    ));
    $installer->getConnection()->addColumn($installer->getTable('ronisbtbanners/banners'), 'updated_at', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_DATETIME,
        'nullable'  => false,
        'comment'   => 'updated at'
    ));
$installer->endSetup();