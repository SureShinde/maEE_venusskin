<?php

/**
 * Class Snowdog_Multilevel_Helper_Profile
 */
class Snowdog_Multilevel_Helper_Profile extends Mage_Core_Helper_Abstract
{
    const PERIOD_DELIMITER = ',';

    public function getValidPeriodFrequencies(Mage_Payment_Model_Recurring_Profile $profile, $includeEmpty = true, $asOptionHash = false)
    {
        $periodFrequencies = $profile->getValidPeriodFrequencies();

        if (is_string($periodFrequencies)) {
            $periodFrequencies = explode(self::PERIOD_DELIMITER, $periodFrequencies);
        }

        sort($periodFrequencies, SORT_NUMERIC);

        $result = array();

        if ($includeEmpty) {
            $result[] = array('value' => '', 'label' => Mage::helper('core')->__('Choose Delivery Frequency'));
        }

        foreach ($periodFrequencies as $periodFrequency) {
            $periodFrequency = (int)trim($periodFrequency);

            if (empty($periodFrequency)) {
                continue;
            }

            $result[] = array('value' => $periodFrequency, 'label' => Mage::helper('multilevel')->__('Every %s %ss', $periodFrequency, $profile->getPeriodUnitLabel($profile->getPeriodUnit())));
        }

        if ($asOptionHash) {
            $options = $result;

            $result = array();

            foreach ($options as $option) {
                $result[$option['value']] = $option['label'];
            }
        }

        return $result;
    }

    public function getAdditionalOptions(Mage_Sales_Model_Quote_Item $item)
    {
        if ($additionalOptions = $item->getOptionByCode('additional_options')) {
            $additionalOptions = unserialize($additionalOptions->getValue());

            if (is_array($additionalOptions)) {
                return $additionalOptions;
            }
        }

        return null;
    }
}
