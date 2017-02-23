<?php
require_once(Mage::getModuleDir('controllers', 'Magestore_Affiliateplus') . DS . 'Adminhtml' . DS . 'AccountController.php');

/**
 * Class Snowdog_Multilevel_Adminhtml_AccountController
 */
class Snowdog_Multilevel_Adminhtml_AccountController extends Magestore_Affiliateplus_Adminhtml_AccountController
{
    public function saveAction()
    {
        if (!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)) {
            return;
        }
        if ($data = $this->getRequest()->getPost()) {
            $accountId = $this->getRequest()->getParam('id');
            $storeId = $this->getRequest()->getParam('store');

            $customer = Mage::getModel('customer/customer')->load($data['customer_id']);

            $email = isset($data['email']) ? $data['email'] : '';
            if (!$accountId && !$customer->getId()) {
                if (!$email || !strpos($email, '@')) {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('Invalid email address'));
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $accountId, 'store' => $storeId));
                    return;
                }

                $websiteId = null;
                if (isset($data['associate_website_id']) && $data['associate_website_id'])
                    $websiteId = $data['associate_website_id'];
                $customer = Mage::getModel('customer/customer')
                    ->setWebsiteId($websiteId)
                    ->loadByEmail($email);
                /* end edit */
                if (!$customer || !$customer->getId()) {
                    try {
                        $websiteId = isset($data['associate_website_id']) ? $data['associate_website_id'] : null;
                        $customer->setEmail($email)
                            ->setWebsiteId(Mage::app()->getWebsite($websiteId)->getId())
                            ->setGroupId($customer->getGroupId())
                            ->setFirstname($data['firstname'])
                            ->setLastname($data['lastname'])
                            ->setForceConfirmed(true);
                        $password = $data['password'];
                        if (!$password)
                            $password = $customer->generatePassword();
                        $customer->setPassword($password);
                        $customer->save();
                        //$customer->sendPasswordReminderEmail();

                    } catch (Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                        Mage::getSingleton('adminhtml/session')->setFormData($data);
                        $this->_redirect('*/*/edit', array('id' => $accountId, 'store' => $storeId));
                        return;
                    }
                } else {
                    $existedAccount = Mage::getModel('affiliateplus/account')->loadByCustomerId($customer->getId());
                    if ($existedAccount->getId())
                        $accountId = $existedAccount->getId();
                    if ($data['password']) {
                        try {
                            $customer->setFirstname($data['firstname'])
                                ->setLastname($data['lastname']);
                            $customer->changePassword($data['password']);
                            $customer->sendPasswordReminderEmail();
                        } catch (Exception $e) {

                        }
                    }
                }
            }

            $address = $customer->getDefaultShippingAddress();

            if ($address && $address->getId())
                $data['address_id'] = $address->getId();

            $beforeAccount = Mage::getModel('affiliateplus/account')->load($accountId);
            $beforeStatusIsDisabled = ($beforeAccount->getStatus() == 2) ? true : false;
            $unapproved = ($beforeAccount->getApproved() == 2) ? true : false;

            $account = Mage::getModel('affiliateplus/account');
            $account->setStoreId($storeId);
            $account->setData($data)->setId($accountId);

            if ($data['top_tire_id']) {
                //change top tier id
                $tier = Mage::getModel('affiliatepluslevel/tier')->getCollection()
                    ->addFieldToFilter('tier_id', $data['tier_id'])
                    ->getFirstItem();
                if ($tier->getId() && $data['tier_id'] != $data['top_tire_id']) {
                    $tier->setToptierId($data['top_tire_id']);
                } else {
                    $tier->setTierId($data['tier_id']);
                    $tier->setToptierId($data['top_tire_id']);
                    $tier->setLevel(1);
                }
                $refererId = $this->_getCustomerIdByAccountId($data['top_tire_id']);
                $customer->setRefererCustomerId($refererId);
                $tier->save();
            } else {
                $tier = Mage::getModel('affiliatepluslevel/tier')->getCollection()
                    ->addFieldToFilter('tier_id', $data['tier_id'])
                    ->getFirstItem();
                $tier->delete();
                $customer->setRefererCustomerId(null);
            }

            try {
                //add event to before save 
                Mage::dispatchEvent('affiliateplus_adminhtml_before_save_account', array('post_data' => $data, 'account' => $account));
                //save customer info
                $customer->setFirstname($data['firstname'])
                    ->setLastname($data['lastname']);
                if ($email && strpos($email, '@'))
                    $customer->setEmail($email);
                $customer->save();

                $account->setName($customer->getName())
                    ->setCustomerId($customer->getId());

                if (!$accountId) {
                    $account->setIdentifyCode($account->generateIdentifyCode())
                        ->setCreatedTime(now())
                        ->setApproved(1)//approved
                    ;
                }

                $account->save();

                if ($accountId) {
                    if ($account->isEnabled() && $beforeStatusIsDisabled && $unapproved) {
                        //send mail to approved account
                        $account->sendMailToApprovedAccount();
                    }
                } else {
                    //send mail to new account
                    $account->sendMailToNewAccount();
                }

                //add event after save
                Mage::dispatchEvent('affiliateplus_adminhtml_after_save_account', array('post_data' => $data, 'account' => $account));
                //ssss

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('affiliateplus')->__('The account has been updated successfully.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $account->getId(), 'store' => $storeId));
                    return;
                }
                $this->_redirect('*/*/', array('store' => $storeId));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $accountId, 'store' => $storeId));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('affiliateplus')->__('Unable to find an account to update'));
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    /**
     * Get customer id by account id
     *
     * @param $accountId
     * @return null
     */
    protected function _getCustomerIdByAccountId($accountId)
    {
        $account = Mage::getModel('affiliateplus/account')->getCollection()
            ->addFieldToFilter('account_id', $accountId)
            ->getFirstItem();

        if ($account) {
            return $account->getCustomerId();
        }
        return null;
    }
}
