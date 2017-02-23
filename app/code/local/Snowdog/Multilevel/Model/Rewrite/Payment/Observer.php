<?php

/**
 * Class Snowdog_Multilevel_Model_Rewrite_Payment_Observer
 */
class Snowdog_Multilevel_Model_Rewrite_Payment_Observer extends Mage_Payment_Model_Observer
{

    public function prepareProductRecurringProfileOptions($observer)
    {
        /**
         * @var Mage_Catalog_Model_Product $product
         */

        $product = $observer->getEvent()->getProduct();
        $buyRequest = $observer->getEvent()->getBuyRequest();

        if (!$product->isRecurring()) {
            return;
        }

        $profile = Mage::getModel('payment/recurring_profile')
            ->setLocale(Mage::app()->getLocale())
            ->setStore(Mage::app()->getStore())
            ->importBuyRequest($buyRequest)
            ->importProduct($product);

        if (!$profile) {
            return;
        }

        $options = $buyRequest->getOptions();

        if (empty($options)) {
            $options = array();
        }

        $options[Snowdog_Multilevel_Model_Rewrite_Payment_Recurring_Profile::BUY_REQUEST_PERIOD] = $profile->getPeriodFrequency();

        $buyRequest->setOptions($options);

        $product->addCustomOption(
            Mage_Payment_Model_Recurring_Profile::PRODUCT_OPTIONS_KEY,
            serialize(
                array(
                    'start_datetime' => $profile->getStartDatetime(),
                    'period_frequency' => $profile->getPeriodFrequency()
                )
            )
        );

        $infoOptions = array(
            array(
                'label' => $profile->getFieldLabel('start_datetime'),
                'value' => $profile->exportStartDatetime(true),
            )
        );

        foreach ($profile->exportScheduleInfo() as $info) {
            $infoOptions[] = array(
                'label' => $info->getTitle(),
                'value' => $info->getSchedule(),
            );
        }

        $product->addCustomOption('additional_options', serialize($infoOptions));
    }

    public function authnetcimRecurringProfileEditBeforeSave(Varien_Event_Observer $observer)
    {
        /**
         * @var Venus_Multilevel_Model_Sales_Recurring_Profile $profile
         */

        $profile = $observer->getEvent()->getProfile();

        if ($periodFrequency = Mage::app()->getRequest()->getPost('period_frequency', null)) {
            $paymentProfile = $profile->getPaymentProfile();

            $validPeriodFrequencies = Mage::helper('multilevel/profile')
                ->getValidPeriodFrequencies($paymentProfile, false, true);

            if (!isset($validPeriodFrequencies[$periodFrequency])) {
                Mage::throwException(Mage::helper('payment')->__('Period frequency is wrong.'));
            }

            if ($profile->getPeriodFrequency() != $periodFrequency
                || $profile->getPeriodUnit() != $paymentProfile->getPeriodUnit()
            ) {
                $profile->addData(
                    array('period_unit' => $paymentProfile->getPeriodUnit(), 'period_frequency' => $periodFrequency)
                )->updateNextCycle();
            }
        }

        return $this;
    }
}
