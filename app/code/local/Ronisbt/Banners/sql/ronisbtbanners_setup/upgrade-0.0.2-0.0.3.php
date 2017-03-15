<?php 
$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->changeColumn($installer->getTable('ronisbtbanners/banners'), 'created_at', 'created_at',array(
    'type'      => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ));
$installer->getConnection()
    ->changeColumn($installer->getTable('ronisbtbanners/banners'), 'updated_at', 'updated_at',array(
    'type'      => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
    ));
$installer->endSetup();