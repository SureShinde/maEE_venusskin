<?php
$installer = $this;
$installer->startSetup();

$collection = Mage::getResourceModel('customer/customer_collection');

/** @var Mage_Customer_Model_Customer $customer */
foreach ($collection as $customer) {
	$customer->setOrderInactivityStep(1);
	$customer->getResource()->saveAttribute($customer, 'order_inactivity_step');
}

$installer->endSetup();
