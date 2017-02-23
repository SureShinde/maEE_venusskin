<?php
class Mage_Adminhtml_Block_Promo_Quote_Edit_Tab_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	const ORDER_ADDRESS_SHIPPING_LABEL = 'shipping';

	public function __construct() {
		parent::__construct();
		$this->setId('couponReportGrid');
		$this->setUseAjax(true);
	}

	protected function _prepareCollection() {
		$priceRuleId = Mage::registry('current_promo_quote_rule')->getId();

		/** @var Mage_Sales_Model_Resource_Order_Collection $collection */
		$collection = Mage::getResourceModel('sales/order_collection');
		$collection->addFieldToFilter('src.rule_id', array('eq' => $priceRuleId))
		           ->addFieldToFilter('sfoa.address_type', array('eq' => self::ORDER_ADDRESS_SHIPPING_LABEL))
		           ->getSelect()
		           ->joinLeft(array('src' => $collection->getTable('salesrule/coupon')), 'src.code = main_table.coupon_code', 'rule_id')
		           ->joinLeft(array('apa' => $collection->getTable('affiliateplus/account')), 'apa.customer_id = main_table.customer_id', array('name', 'account_id'))
		           ->joinLeft(array('aplt' => $collection->getTable('affiliatepluslevel/tier')), 'aplt.tier_id = apa.account_id', 'toptier_id')
		           ->joinLeft(array('apa_ref' => $collection->getTable('affiliateplus/account')), 'apa_ref.account_id = aplt.toptier_id', array('referral_name' => 'name'))
		           ->joinLeft(array('sfoa' => $collection->getTable('sales/order_address')), 'sfoa.parent_id = main_table.entity_id', array('address_type', 'country_id', 'region', 'city', 'postcode'))
		           ->group('main_table.entity_id');

		$this->setCollection($collection);

		return parent::_prepareCollection();
	}

	protected function _prepareColumns() {
		$this->addColumn(
			'grid_increment_id', array(
				              'header'       => Mage::helper('salesrule')->__('Order ID'),
				              'index'        => 'increment_id',
				              'filter_index' => 'main_table.increment_id'
			              )
		);

		$this->addColumn(
			'grid_created_at', array(
				            'header' => Mage::helper('salesrule')->__('Order Date'),
				            'index'  => 'created_at',
				            'type'   => 'datetime',
			            )
		);

		$this->addColumn(
			'grid_coupon_code', array(
				             'header' => Mage::helper('salesrule')->__('Coupon Code Used'),
				             'index'  => 'coupon_code'
			             )
		);

		$this->addColumn(
			'grid_patient_name', array(
				              'header' => Mage::helper('salesrule')->__('Patient Name'),
				              'index'  => 'name'
			              )
		);

		$this->addColumn(
			'grid_referral_name', array(
				               'header' => Mage::helper('salesrule')->__('Referred By'),
				               'index'  => 'referral_name'
			               )
		);

		$this->addColumn(
			'grid_country_id', array(
				            'header' => Mage::helper('affiliateplus')->__('Country'),
				            'align'  => 'left',
				            'index'  => 'country_id',
			            )
		);

		$this->addColumn(
			'grid_region', array(
				        'header' => Mage::helper('affiliateplus')->__('State/Province'),
				        'align'  => 'left',
				        'index'  => 'region',
			        )
		);

		$this->addColumn(
			'grid_city', array(
				      'header' => Mage::helper('affiliateplus')->__('City'),
				      'align'  => 'left',
				      'index'  => 'city',
			      )
		);

		$this->addColumn(
			'grid_postcode', array(
				          'header' => Mage::helper('affiliateplus')->__('Postal Code'),
				          'align'  => 'left',
				          'index'  => 'postcode',
			          )
		);

		$currencyCode = Mage::app()->getStore()->getBaseCurrency()->getCode();

		$this->addColumn(
			'grid_base_grand_total', array(
				                  'header'        => Mage::helper('affiliateplus')->__('Total Amount'),
				                  'width'         => '150px',
				                  'align'         => 'right',
				                  'index'         => 'base_grand_total',
				                  'type'          => 'price',
				                  'currency_code' => $currencyCode,
			                  )
		);

		$this->addExportType('*/*/exportCouponsCsv', Mage::helper('customer')->__('CSV'));

		return $this;
	}

	public function getRowUrl($row) {
		if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
			return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
		}

		return false;
	}

	public function getGridUrl() {
		return $this->getUrl('*/*/couponReportGrid', array('_current' => true));
	}
}
