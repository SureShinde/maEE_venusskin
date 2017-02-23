<?php
/** @var Mage_Customer_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('admin/user');

$installer->getConnection()
          ->addColumn(
	          $tableName,
	          'clinic_account_id',
	          array(
		          'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
		          'nullable' => true,
		          'default'  => '',
		          'comment'  => 'Clinic Account ID'
	          )
          );

$installer->endSetup();
