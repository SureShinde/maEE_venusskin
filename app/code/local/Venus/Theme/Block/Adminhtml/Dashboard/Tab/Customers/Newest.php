<?php

class Venus_Theme_Block_Adminhtml_Dashboard_Tab_Customers_Newest extends Mage_Adminhtml_Block_Dashboard_Tab_Customers_Newest
{
    const MAIN_TABLE_PREFIX = 'main_table';
    const DEFAULT_PAGE_SIZE = 5;

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('affiliateplus/account')->getCollection();

        if ($this->getParam('store')) {
            $collection->addFieldToFilter('apc.store_id', $this->getParam('store'));
        } else if ($this->getParam('website')) {
            $storeIds = Mage::app()->getWebsite($this->getParam('website'))->getStoreIds();
            $collection->addFieldToFilter('apc.store_id', array('in' => $storeIds));
        } else if ($this->getParam('group')) {
            $storeIds = Mage::app()->getGroup($this->getParam('group'))->getStoreIds();
            $collection->addFieldToFilter('apc.store_id', array('in' => $storeIds));
        }

        $collection->setPageSize(self::DEFAULT_PAGE_SIZE)
            ->addFieldToSelect(array('main_account_name' => 'name', 'main_created_time' => 'created_time'))
            ->addFieldToFilter('tier_id', array('notnull' => true))
            ->getSelect()
            ->joinLeft(array('aplt' => $collection->getTable('affiliatepluslevel/tier')), 'aplt.tier_id = main_table.account_id', array('tier_id', 'toptier_id'))
            ->joinLeft(array('apa' => $collection->getTable('affiliateplus/account')), 'apa.account_id = aplt.toptier_id', array('referral_name' => 'name'))
            ->order('main_created_time DESC');


        $period = $this->getRequest()->getParam('period', '0');
        if ($period != 'lifetime') {
            $dates = Mage::helper('theme')->getDateRangeForMonthly($period, 0, 0, false);
            $collection->addFieldToFilter('main_table.created_time', array('gteq' => $dates['from']));
            $collection->addFieldToFilter('main_table.created_time', array('lteq' => $dates['to']));
        }
        
        $this->setCollection($collection);

        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'name', array(
                'header' => $this->__('Customer Name'),
                'sortable' => false,
                'index' => 'main_account_name'
            )
        );

        $this->addColumn(
            'customer_since', array(
                'header' => Mage::helper('customer')->__('Registration Date'),
                'type' => 'datetime',
                'sortable' => false,
                'index' => 'main_created_time',
                'gmtoffset' => true
            )
        );

        $this->addColumn(
            'referral_name', array(
                'header' => $this->__('Referred By'),
                'sortable' => false,
                'index' => 'referral_name'
            )
        );

        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);

        return $this;
    }
}
