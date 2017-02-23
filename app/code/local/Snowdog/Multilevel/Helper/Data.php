<?php

/**
 * Class Snowdog_Multilevel_Helper_Data
 */
class Snowdog_Multilevel_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_DEFAULT_DOCTOR = 'affiliateplus/general/default_doctor';

    const XML_PATH_DEFAULT_PROGRAM = 'affiliateplus/general/default_program';

    const XML_PATH_PEM_ROLE_ID = 'affiliateplus/general/pem_role_id';

    const PARAM_REFERRAL_KEY = 'referer';

    const PARAM_INVITATION_ID = 'invitation_id';

    const XML_PATH_REFERRAL_PURCHASE_CREDIT_AMOUNT = 'affiliateplus/referral_credit/amount';

    const FIRST_PURCHASE_FLAG = 1;

    const CODE = 'customer_balance';

    /**
     * Get default doctor
     *
     * @param null $store
     * @return mixed
     */
    public function getDefaultDoctor($store = null)
    {
        return Mage::getModel('affiliateplus/account')->load(
            Mage::getStoreConfig(self::XML_PATH_DEFAULT_DOCTOR, $store),
            'customer_id'
        );
    }

    /**
     * Get referer key
     *
     * @return mixed
     */
    public function getRefererRequest()
    {
        return $this->_getRequest()->getParam(self::PARAM_REFERRAL_KEY, false);
    }

    public function getParams()
    {
        return $this->_getRequest()->getParams();
    }

    /**
     * ?
     *
     * @param $user
     * @return bool
     * @throws Mage_Core_Exception
     */
    public function isValidInCurrentWebsite()
    {
        $code = $this->_getRequest()->getPost('referer');
        $account = Mage::getModel('affiliateplus/account')->load($code, 'identify_code');

        $customer = Mage::getModel('customer/customer')->load($account->getCustomerId());

        if ($customer->getWebsiteId() == 0) {
            $admin = Mage::getModel('admin/user')->load($customer->getAdminId());
            $validWebsites = $admin->getRole()->getGwsWebsites();
            if (is_string($validWebsites)) {
                $validWebsites = explode(',', $validWebsites);
            }

            return in_array(Mage::app()->getWebsite()->getId(), $validWebsites);
        } else {
            if ($customer->getWebsiteId() == Mage::app()->getWebsite()->getId()) {
                return true;
            }
            return false;
        }
    }

    public function getInvalidReferralCodeMessage()
    {
        return $this->__('You have entered an invalid access code, please try again!');
    }

    public function getInvalidWebsiteReferralCodeMessage()
    {
        return $this->__('The exclusive access code you entered is not valid 
        in this country. Please use the store selector above.');
    }

    /**
     * Get referer url
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getReferralUrl($route = '', $params = array())
    {
        if (!isset($params['_query'])) {
            $params['_query'] = array();
        }

        $params['_query'][self::PARAM_REFERRAL_KEY] = Mage::getSingleton('multilevel/customer')
            ->getAffiliateAccount(Mage::getSingleton('customer/session')->getCustomer())->getIdentifyCode();

        return $this->_getUrl($route, $params);
    }

    /**
     * Get Ref code
     *
     * @return mixed
     */
    public function getReferralCode()
    {
        return Mage::getSingleton('multilevel/customer')
            ->getAffiliateAccount(Mage::getSingleton('customer/session')
                ->getCustomer())->getIdentifyCode();
    }

    /**
     * Get default program ID
     *
     * @param null $store
     * @return bool|mixed
     */
    public function getDefaultPatientProgramId($store = null)
    {
        $id = Mage::getStoreConfig(self::XML_PATH_DEFAULT_PROGRAM, $store);

        if (empty($id)) {
            return false;
        }

        return $id;
    }

    /**
     * Get ref code from url
     *
     * @return mixed
     */
    public function getRefererCode()
    {
        return $this->getRequest()->getParam('referer');
    }

    public function isCustomer($email)
    {
        $adminUser = Mage::getModel('admin/user')->load($email, 'email');

        return null === $adminUser->getId();
    }

    public function getRefererAccount($accountId)
    {
        $refererCustomer = Mage::getModel('enterprise_invitation/invitation')->load($accountId, 'referral_id');

        return $refererCustomer;
    }

    public function saveBalance($amount, $id)
    {
        $balance = Mage::getModel('enterprise_customerbalance/balance')->setCustomerId($id)
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByCustomer();

        $current_balance = $balance->getAmount();
        $comment = '';

        // add store credit
        $balance->setAmount($current_balance);
        $balance->setAmountDelta($amount);
        $balance->setUpdatedActionAdditionalInfo($comment);
        $balance->setHistoryAction(1);
        $balance->save();
    }

    public function getBalance()
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        if (!$customerId) {
            return 0;
        }
        $model = Mage::getModel('enterprise_customerbalance/balance')->load($customerId, 'customer_id');

        return $model->getAmount();
    }

    public function getCreateAccountErrorMessage()
    {
        return $this->__('Account creation is allowed through access codes only at this time. Please check back soon!');
    }

    public function setInvitationFlag($value)
    {
        Mage::getSingleton('customer/session')->setInvitationFlag($value);
    }

    public function getInvitationFlag()
    {
        return  Mage::getSingleton('customer/session')->getInvitationFlag();
    }

    /**
     * Get invitation url
     */
    public function getInvitationUrl($route = '', $params = array())
    {
        if (!isset($params['_query'])) {
            $params['_query'] = array();
        }

        $customerId = Mage::getSingleton('customer/session')->getId();
        $params['invitation_id'] = $customerId;
        $tier = $this->getTier($customerId, 'customer_id');
        $topTierId = Mage::helper('affiliatepluslevel')->getToptierIdByTierId($tier->getId());
        $topTier = $this->getTier($topTierId, 'account_id');
        $params['acc'] = $topTier->getIdentifyCode();
        $storeCode = Mage::app()->getStore()->getCode();
        $websitecode = Mage::app()->getWebsite()->getCode();

        return $this->_getUrl($route, $params)."&_store=$storeCode&___website=$websitecode";
    }

    public function getTier($customerId, $field)
    {
        $tier = Mage::getModel('affiliateplus/account')->getCollection()
            ->addFieldToFilter($field, $customerId)
            ->getFirstItem();

        if ($tier) {
            return $tier;
        }
        return null;
    }
}