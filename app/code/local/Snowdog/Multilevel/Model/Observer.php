<?php

/**
 * Class Snowdog_Multilevel_Model_Observer
 */
class Snowdog_Multilevel_Model_Observer
{
    public function customerAccountCreatePostPredispatch(Varien_Event_Observer $observer)
    {
        $controller = $observer->getEvent()->getControllerAction();

        $refererCode = Mage::helper('multilevel')->getRefererRequest();

        if (empty($refererCode)) {
            $doctor = Mage::helper('multilevel')->getDefaultDoctor();

            $refererCode = $doctor->getIdentifyCode();
        }

        $account = Mage::getModel('affiliateplus/account')->loadByIdentifyCode($refererCode);

        if (!$account->getId() || !Mage::helper('multilevel')->isValidInCurrentWebsite()) {
            $this->_addErrorMessage(
                !$account->getId() ? Mage::helper('multilevel')->getInvalidReferralCodeMessage()
                    : Mage::helper('multilevel')->getInvalidWebsiteReferralCodeMessage()
            );

            Mage::getSingleton('customer/session')->setCustomerFormData($controller->getRequest()->getPost());
            $controller->getResponse()->setRedirect(Mage::helper('customer')->getRegisterUrl());
            $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
        } else if ($account->getId()) {
            Mage::getSingleton('customer/session')->setRefererCode($refererCode);
        }

        $params = Mage::helper('multilevel')->getParams();
        $invitationId = isset($params['invitation_id']) ? $params['invitation_id'] : null;
        if ($invitationId) {
            $this->_createInvitation($invitationId, $params);
        }

        return $this;
    }

    protected function _createInvitation($invitationId, $params)
    {
        try {
            $currentStoreId = Mage::app()->getStore()->getStoreId();
            Mage::getModel('enterprise_invitation/invitation')->setData(
                array(
                    'customer_id' => $invitationId,
                    'email' => $params['email'],
                    'store_id' => $currentStoreId,
                    'message' => '',
                    'group_id' => 1,
                    'invitation_date' => date('Y-m-d H:i:s'),
                )
            )->save();
        } catch (Exception $e) {
        }
    }

    protected function _acceptInvitation($invitationId, $params)
    {
        try {
            Mage::getModel('enterprise_invitation/invitation')->load($params['email'], 'email')
                ->setStatus(Enterprise_Invitation_Model_Invitation::STATUS_ACCEPTED)
                ->setSignupDate(date('Y-m-d H:i:s'))
                ->save();

        } catch (Exception $e) {
        }
    }

    public function customerSaveAfter(Varien_Event_Observer $observer)
    {
        /**
         * @var Mage_Customer_Model_Customer $customer
         */
        $customer = $observer->getEvent()->getCustomer();
        $params = Mage::helper('multilevel')->getParams();

        $invitationId = isset($params['invitation_id']) ? $params['invitation_id'] : null;
        if ($invitationId) {
            $this->_acceptInvitation($invitationId, $params);
        }

        if (Mage::getDesign()->getArea() == 'adminhtml') {
            if ($customer->getCreatedAt() == $customer->getUpdatedAt()) {
                $customer->setFullAffiliate(1);
            }
            return;
        }
        $customer->setFullAffiliate(0);
        $helper = Mage::helper('multilevel/data');
        $invitationFlag = $helper->getInvitationFlag();
        if ($invitationFlag) {
            $amount = Mage::getStoreConfig($helper::XML_PATH_REFERRAL_PURCHASE_CREDIT_AMOUNT);
            $helper->saveBalance($amount, $customer->getId());
            $helper->setInvitationFlag(0);
        }

        $account = Mage::getModel('affiliateplus/account')->load($customer->getId(), 'customer_id');

        if ($account->getId()) {
            return $this;
        }

        $address = $customer->getDefaultShippingAddress();

        $data = array(
            'customer_id' => $customer->getId(),
            'email' => $customer->getEmail(),
            'associate_website_id' => $customer->getWebsiteId(),
            'name' => $customer->getName(),
            'status' => 1,
            'notification' => 1,
            'approved' => 1
        );

        if (!empty($address) && $address->getId()) {
            $data['address_id'] = $address->getId();
        }

        $account->setData($data)
            ->setIdentifyCode($account->generateIdentifyCode())
            ->setCreatedTime(now())
            ->save();

        $customer->setAffiliateAccount($account);


        Mage::getSingleton('multilevel/tier')
            ->establishTiers($customer)
            ->save();

        $programId = Mage::helper('multilevel')->getDefaultPatientProgramId();

        if (!empty($programId)) {
            $programAccount = Mage::getResourceModel('affiliateplusprogram/account_collection')
                ->addFieldToFilter('program_id', $programId)
                ->addFieldToFilter('account_id', $account->getId())
                ->getFirstItem();

            if (empty($programAccount) || !$programAccount->getId()) {
                try {
                    $programAccount = Mage::getResourceModel('affiliateplusprogram/account_collection')
                        ->addFieldToFilter('account_id', $account->getId())->getFirstItem();

                    if (!empty($programAccount) && $programAccount->getId()) {
                        $prevProgram = Mage::getModel('affiliateplusprogram/program')
                            ->load($programAccount->getProgramId());

                        $prevProgram->setNumAccount($prevProgram->getNumAccount() - 1)->save();

                        $programAccount->delete();
                    }

                    $program = Mage::getModel('affiliateplusprogram/program')->load($programId);

                    $programAccount = Mage::getModel('affiliateplusprogram/account')
                        ->setAccountId($account->getId())
                        ->setProgramId($program->getId())
                        ->setJoined(now())
                        ->save();

                    $program->setNumAccount($program->getNumAccount() + 1)->save();

                    Mage::getModel('affiliateplusprogram/joined')
                        ->load($programAccount->getAccountId(), 'account_id')->delete();
                    Mage::getModel('affiliateplusprogram/joined')
                        ->insertJoined($program->getId(), $programAccount->getAccountId());
                } catch (Exception $e) {
                    $this->_addErrorMessage($e->getMessage());
                }
            }
        }

        return $this;
    }

    public function customerSaveBeforeFrontend(Varien_Event_Observer $observer)
    {
        /**
         * @var Mage_Customer_Model_Customer $customer
         */
        $customer = $observer->getEvent()->getCustomer();
        if ($customer->isObjectNew()) {
            if ($refererCode = Mage::getSingleton('customer/session')->getRefererCode()) {
                $affiliateAccount = Mage::getModel('affiliateplus/account')->loadByIdentifyCode($refererCode);

                if (!$affiliateAccount->getId()) {
                    Mage::throwException(Mage::helper('multilevel')->getCreateAccountErrorMessage());
                }

                $referralCustomer = Mage::getModel('multilevel/customer')->load($affiliateAccount->getCustomerId());

                if (!Mage::helper('multilevel')->isValidInCurrentWebsite()) {
                    Mage::throwException(Mage::helper('multilevel')->getInvalidWebsiteReferralCodeMessage());
                }

                if (!$referralCustomer->getId()) {
                    Mage::throwException(Mage::helper('multilevel')->getCreateAccountErrorMessage());
                }

                $customer->setRefererCustomerId($referralCustomer->getId());
            } else {
                Mage::throwException(Mage::helper('multilevel')->getCreateAccountErrorMessage());
            }
        }

        return $this;
    }


    protected function _addSuccessMessage($message)
    {
        if (!Mage::app()->getStore()->isAdmin()) {
            Mage::getSingleton('customer/session')->addSuccess($message);
        }

        return $this;
    }

    protected function _addErrorMessage($message)
    {
        if (!Mage::app()->getStore()->isAdmin()) {
            $session = Mage::getSingleton('customer/session');

            foreach ($session->getMessages()->getErrors() as $error) {
                /**
                 * @var Mage_Core_Model_Message_Abstract $error
                 */

                if ($error->getText() == $message) {
                    return $this;
                }
            }
            $session->addError($message);
        }

        return $this;
    }

    public function registerInvitation(Varien_Event_Observer $observer)
    {
        /**
         * @var Enterprise_Invitation_Customer_AccountController $controller
         */

        $controller = $observer->getEvent()->getControllerAction();

        if ($invitation = Mage::helper('core')->urlDecode($controller->getRequest()->getParam('invitation', false))) {
            $invitation = Mage::getModel('enterprise_invitation/invitation')->loadByInvitationCode($invitation);

            $account = Mage::getModel('affiliateplus/account')->loadByCustomerId($invitation->getCustomerId());

            if (!$account->getId()) {
                $this->_addErrorMessage(Mage::helper('multilevel')->getCreateAccountErrorMessage());

                $controller->getResponse()->setRedirect(Mage::helper('customer')->getLoginUrl());
                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            } else {
                Mage::getSingleton('customer/session')->setRefererCode($account->getIdentifyCode());
                Mage::getSingleton('customer/session')->setInvitationFlag(1);
            }
        }

        return $this;
    }

    public function sendReportEmail()
    {
        $websites = Mage::app()->getWebsites(true);
        $helper = Mage::helper('affiliateplus/config');
        foreach ($websites as $website) {
            if (!$website->getConfig('affiliateplus/email/is_sent_report'))
                continue;
            $periodData = array(
                'week' => array(
                    'date' => 'w',
                    'label' => $helper->__('last week'),
                ),
                'month' => array(
                    'date' => 'j',
                    'label' => $helper->__('last month'),
                ),
                'year' => array(
                    'date' => 'z',
                    'label' => $helper->__('last year'),
                )
            );
            $period = $website->getConfig('affiliateplus/email/report_period');
            if (date($periodData[$period]['date']) != 1)
                continue;

            $store = $website->getDefaultStore();
            if (!$store)
                continue;
            $storeId = $store->getId();

            $accounts = Mage::getResourceModel('affiliateplus/account_collection')
                ->addFieldToFilter('main_table.status', 1)
                ->addFieldToFilter('main_table.notification', 1);

            if (!$website->getConfig('affiliateplus/email/is_sent_report')) {
                $customerIds = Mage::getModel('customer/customer')->getCollection()
                    ->addAttributeToFilter('full_affiliate', 1)
                    ->getAllIds();
                $accounts->addFieldToFilter('customer_id', array('in' => $customerIds));
            }

            $accounts->getSelect()->joinLeft(
                array('e' => $accounts->getTable('customer/entity')), 'main_table.customer_id	= e.entity_id', array('website_id')
            )->where('e.website_id = ?', $website->getId())
                ->where('e.is_active = 1');
            $date = new Zend_Date();
            $to = $date->toString();
            $to = Mage::getModel('core/locale')->storeDate(Mage::app()->getStore(), $to, true)
                ->toString('dd-MM-YYYY HH:mm:ss');
            $function = 'sub' . ucfirst($period);
            $fromDate = $date->$function(1)->toString('YYYY-MM-dd');
            $from = $date->toString();
            $from = Mage::getModel('core/locale')->storeDate(Mage::app()->getStore(), $from, true)
                ->toString('dd-MM-YYYY HH:mm:ss');

            $translate = Mage::getSingleton('core/translate');
            $translate->setTranslateInline(false);
            $template = $website->getConfig('affiliateplus/email/report_template');
            $sender = Mage::getStoreConfig('trans_email/ident_sales', $store);

            foreach ($accounts as $account) {
                $statistic = new Varien_Object();
                $transactions = Mage::getResourceModel('affiliateplus/transaction_collection')
                    ->addFieldToFilter('account_id', $account->getId());
                $transactions->getSelect()->reset(Zend_Db_Select::COLUMNS)
                    ->where('date(created_time) >= ?', $fromDate)
                    ->columns(array(
                        'status',
                        'sales' => 'SUM(`total_amount`)',
                        'transactions' => 'COUNT(`transaction_id`)',
                        'commissions' => 'SUM(`commission`+`commission`*`percent_plus`+`commission_plus`)',
                    ))->group('status');
                foreach ($transactions as $transaction) {
                    if ($transaction->getStatus() == 1) {
                        $statistic->setData('complete', $transaction->getData());
                    } elseif ($transaction->getStatus() == 2) {
                        $statistic->setData('pending', $transaction->getData());
                    } elseif ($transaction->getStatus() == 3) {
                        $statistic->setData('cancel', $transaction->getData());
                    }
                }

                $actions = Mage::getResourceModel('affiliateplus/action_collection');
                $actions->getSelect()->reset(Zend_Db_Select::COLUMNS)
                    ->where('account_id = ?', $account->getId())
                    ->where('type = ?', 2)
                    ->where('created_date >= ?', $fromDate)
                    ->columns(array(
                        'clicks' => 'SUM(totals)',
                        'unique' => 'SUM(is_unique)',
                    ))->group('account_id');
                $statistic->setData('click', $actions->getFirstItem()->getData());
                Mage::getModel('core/email_template')
                    ->setDesignConfig(array(
                        'area' => 'frontend',
                        'store' => $storeId,
                    ))->sendTransactional(
                        $template,
                        $sender,
                        $account->getEmail(),
                        $account->getName(),
                        array(
                            'store' => $store,
                            'account' => $account,
                            'statistic' => $statistic,
                            'period' => $helper->__($period),
                            'label' => $periodData[$period]['label'],
                            'from' => $from,
                            'to' => $to,
                        )
                    );
            }

            $translate->setTranslateInline(true);
        }
    }

    /**
     * Add gift wrapping info for item to pdf (invoice, creditmemo)
     *
     * @param Varien_Event_Observer $observer
     */
    public function addGiftWrappingInfoForItemToPdf(Varien_Event_Observer $observer)
    {
        $entityItem = $observer->getEvent()->getEntityItem();
        $orderItem  = $entityItem->getOrderItem();
        if($orderItem) {
            if (!$orderItem->getGwPrice()) {
                return;
            }

            $transportObject = $observer->getEvent()->getTransportObject();
            $rendererTypeList = $transportObject->getRendererTypeList();
            $rendererTypeList['giftwrapping'] = 'giftwrapping';
            $transportObject->setRendererTypeList($rendererTypeList);
        }
    }
}
