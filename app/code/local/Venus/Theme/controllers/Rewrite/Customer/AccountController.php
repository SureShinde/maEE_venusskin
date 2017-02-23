<?php
require_once Mage::getModuleDir('controllers', 'Mage_Customer') . DS . 'AccountController.php';

class Venus_Theme_Rewrite_Customer_AccountController extends Mage_Customer_AccountController
{
    protected $_redirectHere = false;

    public function createPostAction()
    {
        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');

            return;
        }
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        if (!$this->getRequest()->isPost()) {
            $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
            $this->_redirectError($errUrl);

            return;
        }

        $customer = $this->_getCustomer();

        try {
            $errors = $this->_getCustomerErrors($customer);

            if (empty($errors)) {
                $customer->addData(
                    array(
                        'website_id' => $this->getRequest()->getPost('country_id') == Venus_Theme_Helper_Data::COUNTRY_CODE_CANADA ? Venus_Theme_Helper_Data::STORE_WEBSITE_ID_CANADA : Mage::app()->getWebsite(true)->getDefaultGroup()->getWebsiteId(),
                    )
                );

                $customer->save();

                if ($this->getRequest()->getPost('redirect_here')) {
                    $this->_redirectHere = true;
                }

                $this->_dispatchRegisterSuccess($customer);
                $this->_successProcessRegistration($customer);

                return;
            } else {
                $this->_addSessionError($errors);
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $url = $this->_getUrl('customer/account/forgotpassword');
                $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
                $session->setEscapeMessages(false);
            } else {
                $message = $e->getMessage();
            }
            $session->addError($message);
        } catch (Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost())
                ->addException($e, $this->__('Cannot save the customer.'));
        }
        $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
        $this->_redirectError($errUrl);
    }

    protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess(
            $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName())
        );
        if ($this->_isVatValidationEnabled()) {
            // Show corresponding VAT message to customer
            $configAddressType = $this->_getHelper('customer/address')->getTaxCalculationAddressType();
            $userPrompt = '';
            switch ($configAddressType) {
                case Mage_Customer_Model_Address_Abstract::TYPE_SHIPPING:
                    $userPrompt = $this->__(
                        'If you are a registered VAT customer, please click <a href="%s">here</a> to enter you shipping address for proper VAT calculation',
                        $this->_getUrl('customer/address/edit')
                    );
                    break;
                default:
                    $userPrompt = $this->__(
                        'If you are a registered VAT customer, please click <a href="%s">here</a> to enter you billing address for proper VAT calculation',
                        $this->_getUrl('customer/address/edit')
                    );
            }
            $this->_getSession()->addSuccess($userPrompt);
        }

        $customer->sendNewAccountEmail(
            $isJustConfirmed ? 'confirmed' : 'registered',
            '',
            Mage::app()->getStore()->getId()
        );

        if ($customer->getWebsiteId() == Mage::app()->getWebsite()->getId()) {
            if ($this->_redirectHere) {
                $successUrl = $this->_getUrl('registration', array('_secure' => true));
            } else {
                $successUrl = $this->_getUrl('*/*/index', array('_secure' => true));
            }
        } else {
            return $this->_getUrl('*/*/login', array('_secure' => true, '_use_rewrite' => true, '_query' => array(Venus_Theme_Helper_Data::URL_QUERY_WEBSITE => Mage::app()->getWebsite($customer->getWebsiteId())->getCode())));
        }

        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }

        return $successUrl;
    }

    protected function _loginPostRedirect()
    {
        if ($this->_getSession()->getCustomer()->getAdminId()) {
            return $this->_redirect('invitation');
        } else {
            return parent::_loginPostRedirect();
        }
    }
}
