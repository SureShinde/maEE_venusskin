<?php
class Venus_Theme_Block_Adminhtml_Dashboard_Tab_Customers_Most extends Mage_Adminhtml_Block_Dashboard_Tab_Customers_Most {
	const DEFAULT_PAGE_SIZE      = 5;
	const ORDER_STATUS_COMPLETED = 1;

	protected function _prepareCollection() {
		$collection = Mage::getModel('affiliateplus/account')->getCollection();
		$collection->addFieldToSelect(array('main_account_id' => 'account_id', 'main_account_name' => 'name'))
		           ->addFieldToFilter('apt.status', array('eq' => self::ORDER_STATUS_COMPLETED))
		           ->addFieldToFilter('au.email', array('null' => true))
		           ->getSelect()
		           ->joinLeft(array('au' => $collection->getTable('admin/user')), 'au.email = main_table.email', array('email'))
		           ->joinLeft(array('apt' => $collection->getTable('affiliateplus/transaction')), 'apt.customer_email = main_table.email', array('count_orders' => new Zend_Db_Expr('COUNT(transaction_id)'), 'sum_total_amount' => new Zend_Db_Expr('SUM(total_amount)'), 'sum_commission' => new Zend_Db_Expr('SUM(commission)'), 'status'))
		           ->group('main_table.account_id')
		           ->order('count_referrals DESC')
		           ->order('sum_commission DESC');

		$subCollection = Mage::getResourceModel('affiliatepluslevel/tier_collection');
		$subCollection->getSelect()
		              ->reset(Zend_Db_Select::COLUMNS)
		              ->joinLeft(array('apar' => $subCollection->getTable('affiliateplus/account')), 'apar.account_id = main_table.tier_id', array('count_referrals' => new Zend_Db_Expr('COUNT(apar.account_id)')))
		              ->where('main_table.toptier_id = main_account_id');

		$period = $this->getRequest()->getParam('period', '0');
		if ($period != 'lifetime') {
			$dates = Mage::helper('theme')->getDateRangeForMonthly($period, 0, 0, false);

			$collection->addFieldToFilter('apt.created_time', array('from' => $dates['from'], 'to' => $dates['to']));
			$subCollection->addFieldToFilter('apar.created_time', array('from' => $dates['from'], 'to' => $dates['to']));
		}

		$collection->setPageSize(self::DEFAULT_PAGE_SIZE)
		           ->getSelect()
		           ->columns(array('count_referrals' => $subCollection->getSelect()));

		$this->setCollection($collection);

		return $this;
	}

	protected function _prepareColumns() {
		$this->addColumn(
			'name', array(
				      'header'   => $this->__('Customer Name'),
				      'sortable' => false,
				      'index'    => 'main_account_name'
			      )
		);

		$this->addColumn(
			'count_orders', array(
				              'header'   => $this->__('Number of Orders'),
				              'sortable' => false,
				              'index'    => 'count_orders',
				              'type'     => 'number'
			              )
		);

		$baseCurrencyCode = (string)Mage::app()->getStore((int)$this->getParam('store'))->getBaseCurrencyCode();

		$this->addColumn(
			'sum_total_paid', array(
				                'header'        => $this->__('Revenue Earned'),
				                'align'         => 'right',
				                'sortable'      => false,
				                'type'          => 'currency',
				                'currency_code' => $baseCurrencyCode,
				                'index'         => 'sum_total_amount'
			                )
		);

		$this->addColumn(
			'sum_commission_earned', array(
				                       'header'        => $this->__('Commission Earned'),
				                       'align'         => 'right',
				                       'sortable'      => false,
				                       'type'          => 'currency',
				                       'currency_code' => $baseCurrencyCode,
				                       'index'         => 'sum_commission'
			                       )
		);

		$this->addColumn(
			'count_referrals', array(
				                 'header'   => $this->__('# of Referrals'),
				                 'align'    => 'right',
				                 'sortable' => false,
				                 'type'     => 'number',
				                 'index'    => 'count_referrals'
			                 )
		);

		$this->setFilterVisibility(false);
		$this->setPagerVisibility(false);

		return $this;
	}

	public function getRowUrl($row) {
		return $this->getUrl('affiliateplusadmin/adminhtml_account/edit', array('id' => $row->getMainAccountId()));
	}
}
