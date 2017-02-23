<?php
/** @var Mage_Customer_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$entityTypeId = $installer->getEntityTypeId('customer');

$installer->removeAttribute($entityTypeId, 'has_redeemed_coupon');
$installer->addAttribute(
	$entityTypeId,
	'has_redeemed_coupon',
	array(
		'type'           => 'int',
		'label'          => 'Has Redeemed Inactivity Coupon',
		'input'          => 'text',
		'visible'        => true,
		'required'       => false,
		'default'        => '0',
		'adminhtml_only' => '1'
	)
);

$installer->removeAttribute($entityTypeId, 'assigned_coupon_code');
$installer->addAttribute(
	$entityTypeId,
	'assigned_coupon_code',
	array(
		'type'           => 'varchar',
		'label'          => 'Assigned Coupon Code',
		'input'          => 'text',
		'visible'        => true,
		'required'       => false,
		'default'        => '',
		'adminhtml_only' => '1'
	)
);

$installer->endSetup();
