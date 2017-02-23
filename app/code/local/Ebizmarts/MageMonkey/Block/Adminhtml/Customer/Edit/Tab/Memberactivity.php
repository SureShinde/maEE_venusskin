<?php
class Ebizmarts_MageMonkey_Block_Adminhtml_Customer_Edit_Tab_Memberactivity extends Ebizmarts_MageMonkey_Block_Adminhtml_Memberactivity_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface {
	protected $_columnsToRemove = array('visitor_session_id', 'protocol', 'customer_id');

	public function __construct() {
		parent::__construct();
		$this->setFilterVisibility(false);
		$this->setSaveParametersInSession(false);
	}

	/**
	 * Defines after which tab, this tab should be rendered
	 *
	 * @return string
	 */
	public function getAfter() {
		return 'orders';
	}

	/**
	 * Return Tab label
	 *
	 * @return string
	 */
	public function getTabLabel() {
		return $this->__('MailChimp List Member Activity');
	}

	/**
	 * Return Tab title
	 *
	 * @return string
	 */
	public function getTabTitle() {
		return $this->__('MailChimp List Member Activity');
	}

	/**
	 * Can show tab in tabs
	 *
	 * @return boolean
	 */
	public function canShowTab() {
		return Mage::getSingleton('admin/session')->isAllowed('customer/manage_view_tabs_mailchimp');
	}

	/**
	 * Tab is hidden
	 *
	 * @return boolean
	 */
	public function isHidden() {
		//TODO: Hide if MageMonkey is disabled
		return false;
	}
}
