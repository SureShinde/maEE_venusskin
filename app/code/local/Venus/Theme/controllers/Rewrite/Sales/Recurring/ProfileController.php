<?php
require_once Mage::getModuleDir('controllers', 'Mage_Sales') . DS . 'Recurring' . DS . 'ProfileController.php';

class Venus_Theme_Rewrite_Sales_Recurring_ProfileController extends Mage_Sales_Recurring_ProfileController {
	protected function _viewAction() {
		$profile = $this->_initProfile();

		if ($profile && $this->_session->getCustomer() && $profile->getCustomerId() == $this->_session->getCustomerId()) {
			try {
				$this->_title($this->__('Recurring Profiles'))->_title($this->__('Profile #%s', $profile->getReferenceId()));
				$this->loadLayout();
				$this->_initLayoutMessages('customer/session');
				$navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
				if ($navigationBlock) {
					$navigationBlock->setActive('sales/recurring_profile/');
				}
				$this->renderLayout();

				return;
			} catch (Mage_Core_Exception $e) {
				$this->_session->addError($e->getMessage());
			} catch (Exception $e) {
				Mage::logException($e);
			}
			$this->_redirect('*/*/');
		} else {
			$this->_redirect('*/*/');
		}
	}

	public function updateStateAction() {
		$profile = $this->_initProfile();

		if ($profile && $this->_session->getCustomer() && $profile->getCustomerId() == $this->_session->getCustomerId()) {
			try {
				switch ($this->getRequest()->getParam('action')) {
					case 'cancel':
						$profile->cancel();
						break;
					case 'suspend':
						$profile->suspend();
						break;
					case 'activate':
						$profile->activate();
						break;
				}
				$this->_session->addSuccess($this->__('The profile state has been updated.'));
			} catch (Mage_Core_Exception $e) {
				$this->_session->addError($e->getMessage());
			} catch (Exception $e) {
				$this->_session->addError($this->__('Failed to update the profile.'));
				Mage::logException($e);
			}
			if ($profile) {
				$this->_redirect('*/*/view', array('profile' => $profile->getId()));
			} else {
				$this->_redirect('*/*/');
			}
		} else {
			$this->_redirect('*/*/');
		}
	}

	protected function _initProfile() {
		$profile = Mage::getModel('sales/recurring_profile')->load($this->getRequest()->getParam('profile'));
		if (!$profile->getId()) {
			return null;
		}
		Mage::register('current_recurring_profile', $profile);

		return $profile;
	}
}
