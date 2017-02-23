<?php
class Venus_Theme_Model_Sales_Resource_Report_Bestsellers_Collection extends Mage_Sales_Model_Resource_Report_Bestsellers_Collection {
	protected function _initSelect() {
		$select = $this->getSelect();

		$cols                = $this->_getSelectedColumns();
		$cols['qty_ordered'] = 'SUM(qty_ordered)';

		$mainTable = $this->getTable('sales/bestsellers_aggregated_monthly');
		$select->from($mainTable, $cols);

		$subSelect = $this->getConnection()->select();
		$subSelect->from(array('existed_products' => $this->getTable('catalog/product')), new Zend_Db_Expr('1'));

		$select->exists($subSelect, $mainTable . '.product_id = existed_products.entity_id')
		       ->group('product_id')
		       ->order('qty_ordered ' . Varien_Db_Select::SQL_DESC)
		       ->limit($this->_ratingLimit);

		return $this;
	}
}
