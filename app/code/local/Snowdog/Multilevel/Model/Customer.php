<?php

/**
 * @method int getAdminId()
 * @method int getOwnerId()
 */
class Snowdog_Multilevel_Model_Customer extends Mage_Customer_Model_Customer
{
    /**
     * Load customer by admin id
     *
     * @param $adminId
     * @return $this
     */
    public function loadByAdminId($adminId)
    {
        $customer = $this->getCollection()->addAttributeToFilter('admin_id', $adminId)->setPage(1, 1)->getFirstItem();

        $this->load($customer->getId());

        return $this;
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     *
     * @return Magestore_Affiliateplus_Model_Account
     */
    public function getAffiliateAccount(Mage_Customer_Model_Customer $customer = null)
    {
        if (null === $customer) {
            $customer = $this;
        }

        if (!$customer->hasAffiliateAccount()) {
            $customer->setData(
                'affiliate_account',
                Mage::getModel('affiliateplus/account')->load($customer->getId(), 'customer_id')
            );
        }

        return $customer->getData('affiliate_account');
    }

    /**
     * Check if customer is Doctor
     *
     * @param Mage_Customer_Model_Customer|null $customer
     * @return bool
     */
    public function isDoctor(Mage_Customer_Model_Customer $customer = null)
    {
        if (null === $customer) {
            $customer = $this;
        }
        $adminId = $customer->getAdminId();

        return !empty($adminId);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     *
     * @return Snowdog_Multilevel_Model_Customer
     */
    public function getRefererCustomer(Mage_Customer_Model_Customer $customer = null)
    {
        if (null === $customer) {
            $customer = $this;
        }

        if (!$customer->hasRefererCustomer()) {
            $customer->setData(
                'referer_customer',
                Mage::getModel('multilevel/customer')->load($customer->getRefererCustomerId())
            );
        }

        return $customer->getData('referer_customer');
    }
}
