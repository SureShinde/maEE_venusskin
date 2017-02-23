<?php
class Inspiratica_Banner_IndexController extends Mage_Core_Controller_Front_Action {
	public function clickAction() {
		$id = $this->getRequest()->getParam("id");
		if ($id) {
			$banner = Mage::getModel("banner/banner")->load($id);
			$banner->click();
			$clickurl = $banner->getUrl();
			if ($clickurl) {
				if (!preg_match('#http[s]?://|index[2]?\.php#', $clickurl)) {
					$clickurl = "http://" . $clickurl;
				}
				$this->getResponse()->setRedirect($clickurl);
				return;
			}
		}
		$this->_redirect('/');
	}

	/*
	public function changeCustomerAction() {
		$customer_id = $this->getRequest()->getParam('customer_id');
		$customer    = Mage::getResourceModel('customer/customer_collection')
			->addNameToSelect()
			->addAttributeToSelect('email')
			->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
			->addAttributeToFilter('entity_id', $customer_id)
			->getFirstItem();
		$html        = '';
		$html .= '<input type="hidden" id="map_customer_name" value="' . $customer->getName() . '" />';
		$html .= '<input type="hidden" id="map_customer_email" value="' . $customer->getEmail() . '" />';
		$html .= '<input type="hidden" id="map_customer_id" value="' . $customer->getId() . '" />';
		$html .= '<input type="hidden" id="map_customer_billing_telephone" value="' . $customer->getBillingTelephone() . '" />';

		$this->getResponse()->setHeader('Content-type', 'application/x-json');
		$this->getResponse()->setBody($html);
	}
	*/
}
