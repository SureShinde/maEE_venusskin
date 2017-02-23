<?php
class Venus_Registration_Model_Observer {
	public function checkAndCreatePhysicianBackendAccount(Varien_Event_Observer $observer) {
		$orderId = $observer->getEvent()->getOrderIds();

		if ($orderId) {
			/** @var Mage_Sales_Model_Order $order */
			$order = Mage::getModel('sales/order')->load($orderId);
			foreach ($order->getAllItems() as $item) {
				if ($item->getProduct()->getPhysiciansOnly()) {
					/** @var Mage_Customer_Model_Customer $customer */
					$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
					$password = Mage::helper('registration')->generatePassword();

					/** @var Mage_Admin_Model_User $adminAccount */
					$adminAccount = Mage::getModel('admin/user');
					$adminAccount->load($customer->getEmail(), 'email');

					if (!$adminAccount->getId()) {
						try {
							$adminAccount->setData(
								array(
									'firstname'             => $customer->getFirstname(),
									'lastname'              => $customer->getLastname(),
									'email'                 => $customer->getEmail(),
									'username'              => Mage::helper('registration')->__('dr-%s', strtolower(preg_replace('/[^a-zA-Z\d]/', '', $customer->getLastname()))),
									'password'              => $password,
									'password_confirmation' => $password,
								)
							);
							$adminAccount->save();

							/** @var Mage_Admin_Model_Role $adminAccountRole */
							$adminAccountRole = Mage::getModel('admin/role');
							$adminAccountRole->setData(
								array(
									'parent_id'  => Mage::getStoreConfig(Venus_Registration_Helper_Data::XML_PATH_DEFAULT_CLINIC_ROLE_ID),
									'tree_level' => Venus_Registration_Helper_Data::NEW_CLINIC_ACCOUNT_ROLE_TREE_LEVEL,
									'role_type'  => 'U',
									'user_id'    => $adminAccount->getId(),
								)
							);
							$adminAccountRole->save();

							$customer->setAdminId($adminAccount->getId())->save();

							/** @var Magestore_Affiliatepluslevel_Model_Tier $tier */
							$tier = Mage::getModel('affiliatepluslevel/tier')->load($adminAccount->getId(), 'tier_id');
							if ($tier->getId()) {
								$tier->delete(); // Removes the default upper tier from the doctor's affiliate account from frontend registration
							}

							Mage::helper('theme/admin')->sendNewAdminEmail($adminAccount);
						} catch (Exception $e) {
							Mage::logException($e);
						}
					}

					break;
				}
			}
		}

		return $this;
	}
}
