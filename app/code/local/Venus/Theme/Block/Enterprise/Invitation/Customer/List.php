<?php

class Venus_Theme_Block_Enterprise_Invitation_Customer_List extends Enterprise_Invitation_Block_Customer_List
{
    public function getReferralStatsCollection()
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();

        $stats = array();

        /** @var Magestore_Affiliateplus_Model_Account $affiliateAccount */
        $affiliateAccount = Mage::getModel('affiliateplus/account')->load($customerId, 'customer_id');

        /** @var Enterprise_Invitation_Model_Resource_Invitation_Collection $collection */
        $collection = Mage::getResourceModel('enterprise_invitation/invitation_collection');

        $collection->addFieldToFilter('main_table.customer_id', array('eq' => $customerId))
            ->getSelect()
            ->joinLeft(
                array('apt' => $collection->getTable('affiliateplus/account')),
                'apt.customer_id = main_table.referral_id',
                array('has_purchased')
            )
            ->group('main_table.email');
        /** @var Enterprise_Invitation_Model_Invitation $item */
        foreach ($collection as $item) {
            if (!isset($stats[$item->getEmail()])) {
                $stats[$item->getEmail()] = array(
                    'has_signed_up' => $item->getStatus() == 'accepted' ? true : false,
                    'has_made_purchase' => $item->getHasPurchased(),
                );
            }
        }

        /** @var Magestore_Affiliateplus_Model_Mysql4_Account_Collection $collection */
        $collection = Mage::getResourceModel('affiliateplus/account_collection');
        $collection->addFieldToFilter('aplt.toptier_id', array('eq' => $affiliateAccount->getAccountId()))
            ->getSelect()
            ->joinLeft(
                array('aplt' => $collection->getTable('affiliatepluslevel/tier')),
                'aplt.tier_id = main_table.account_id',
                array('toptier_id')
            );

        /** @var Magestore_Affiliateplus_Model_Account $item */
        foreach ($collection as $item) {
            if (!isset($stats[$item->getEmail()])) {
                $stats[$item->getEmail()] = array(
                    'has_signed_up' => true,
                    'has_made_purchase' => $item->getHasPurchased(),
                );
            }
        }

        return $stats;
    }

    /**
     * Get customer id
     *
     * @return int
     */
    public function getCustomerId()
    {
        return Mage::getSingleton('customer/session')->getId();
    }
}
