<?php
class Venus_Theme_Block_Adminhtml_Sales_Order_Create_Totals_Nominal extends Mage_Adminhtml_Block_Sales_Order_Create_Totals_Default {
	protected $_template = 'sales/order/create/totals/nominal.phtml';

	public function getItemName(Mage_Sales_Model_Quote_Item_Abstract $quoteItem) {
		return $quoteItem->getName();
	}

	public function getItemRowTotal(Mage_Sales_Model_Quote_Item_Abstract $quoteItem) {
		return $quoteItem->getNominalRowTotal();
	}

	public function getTotalItemDetails(Mage_Sales_Model_Quote_Item_Abstract $quoteItem) {
		return $quoteItem->getNominalTotalDetails();
	}

	public function getItemDetailsRowLabel(Varien_Object $row) {
		return $row->getLabel();
	}

	public function getItemDetailsRowAmount(Varien_Object $row) {
		return $row->getAmount();
	}

	public function getItemDetailsRowIsCompounded(Varien_Object $row) {
		return $row->getIsCompounded();
	}

	protected function _toHtml() {
		$this->getQuote()->collectTotals();

		$total = $this->getTotal();
		$items = $total->getItems();

		if ($items) {
			foreach ($total->getData() as $key => $value) {
				$this->setData("total_{$key}", $value);
			}

			return parent::_toHtml();
		}

		return '';
	}
}
