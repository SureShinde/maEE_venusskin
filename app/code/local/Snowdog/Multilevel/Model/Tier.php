<?php

/**
 * @method Snowdog_Multilevel_Model_Mysql4_Tier _getResource()
 */
class Snowdog_Multilevel_Model_Tier extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();

        $this->_init('multilevel/tier');
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @param Mage_Customer_Model_Customer $referer
     *
     * @return Venus_Multilevel_Model_Resource_Tier_Collection
     */
    public function establishTiers(Mage_Customer_Model_Customer $customer, Mage_Customer_Model_Customer $referer = null)
    {
        $customerAffiliateAccount = $this->_getCustomerSingleton()->getAffiliateAccount($customer);

        $collection = Mage::getResourceModel($this->_resourceCollectionName, $this->_getResource())
            ->addFieldToFilter('tier_id', $customerAffiliateAccount->getId());

        if (!$this->_getCustomerSingleton()->isDoctor($customer)) {
            if (null === $referer) {
                $referer = Mage::getModel('multilevel/customer')->getRefererCustomer($customer);
            }

            $refererAffiliateAccount = $this->_getCustomerSingleton()->getAffiliateAccount($referer);
            $collection->addFieldToFilter('toptier_id', $refererAffiliateAccount->getId());

            if ($refererAffiliateAccount->getId() && $collection->count() == 0) {
                $item = Mage::getModel('multilevel/tier')->setData(
                    array(
                        'tier_id' => $customerAffiliateAccount->getId(),
                        'toptier_id' => $refererAffiliateAccount->getId(),
                        'level' => 1
                    )
                );
                $collection->addItem($item);
            }
        }

        return $collection;
    }

    public function getTopMostTierId($tierId)
    {
        $topTierId = $this->_getResource()->getTopTierId($tierId);

        $tierId = $topTierId;
        while (!empty($tierId)) {
            $tierId = $this->_getResource()->getTopTierId($tierId);

            if (!empty($tierId)) {
                $topTierId = $tierId;
            }
        }

        return $topTierId;
    }

    /**
     * @return Venus_Multilevel_Model_Customer
     */
    protected function _getCustomerSingleton()
    {
        return Mage::getSingleton('multilevel/customer');
    }
}
