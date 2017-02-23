<?php
require_once Mage::getModuleDir('controllers', 'Enterprise_Rma') . DS . 'Adminhtml' . DS . 'RmaController.php';

class Venus_Theme_Rewrite_Rma_Adminhtml_RmaController extends Enterprise_Rma_Adminhtml_RmaController {
	public function saveShippingAction() {
		$responseAjax = new Varien_Object();

		try {
			$model = $this->_initModel();
			if ($model) {
				if ($this->_createShippingLabel($model)) {
					$this->_getSession()
					     ->addSuccess($this->__('The shipping label has been created.'));
					$responseAjax->setOk(true);
					$this->setEmailComment();
				}
				Mage::getSingleton('adminhtml/session')->getCommentText(true);
			} else {
				$this->_forward('noRoute');

				return;
			}
		} catch (Mage_Core_Exception $e) {
			$responseAjax->setError(true);
			$responseAjax->setMessage($e->getMessage());
		} catch (Exception $e) {
			Mage::logException($e);
			$responseAjax->setError(true);
			$responseAjax->setMessage(
				Mage::helper('enterprise_rma')->__('An error occurred while creating shipping label.')
			);
		}
		$this->getResponse()->setBody($responseAjax->toJson());
	}

	public function setEmailComment() {
		$rma = Mage::registry('current_rma');
		if (!$rma) {
			Mage::throwException(Mage::helper('enterprise_rma')->__('Invalid RMA.'));
		}

		/** @var $history Enterprise_Rma_Model_Rma_Status_History */
		$history = Mage::getModel('enterprise_rma/rma_status_history');
		$history->setRmaEntityId((int)$rma->getId())
		        ->setComment(Mage::helper('enterprise_rma')->__('Shipping label is now available for print at: http://venus.inspi.ca/rma/return/history/'))
		        ->setIsVisibleOnFront(true)
		        ->setIsCustomerNotified(true)
		        ->setStatus($rma->getStatus())
		        ->setCreatedAt(Mage::getSingleton('core/date')->gmtDate())
		        ->setIsAdmin(1)
		        ->save();

		$history->setRma($rma);
		$history->setStoreId($rma->getStoreId());
		$history->sendCommentEmail();
	}
}
