<?php
$installer = $this;
$installer->startSetup();

$collection = Mage::getResourceModel('customer/customer_collection');

/** @var Mage_Customer_Model_Customer $customer */
foreach ($collection as $customer) {
	$customer->setHasRedeemedCoupon(0)
	         ->setAssignedCouponCode('');
	$customer->save();
}

$installer->endSetup();
