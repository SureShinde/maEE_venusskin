<?php
/** @var Mage_Customer_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$entityTypeId = $installer->getEntityTypeId('customer');

$installer->removeAttribute($entityTypeId, 'order_inactivity_step');
$installer->addAttribute(
	$entityTypeId,
	'order_inactivity_step',
	array(
		'type'           => 'int',
		'label'          => 'Order Inactivity Step',
		'input'          => 'text',
		'visible'        => true,
		'required'       => false,
		'default'        => '1',
		'adminhtml_only' => '1'
	)
);

$installer->endSetup();
