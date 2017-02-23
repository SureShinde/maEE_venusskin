<?php

class Venus_Theme_Helper_Data extends Mage_Core_Helper_Abstract
{
    const URL_QUERY_WEBSITE = '___website';
    const CANADA_WEBSITE_CODE = 'base';
    const COUNTRY_CODE_CANADA = 'CA';
    const STORE_WEBSITE_ID_CANADA = 1;
    const ADMIN_ROLE_ID = 1;

    public function getDateRangeForMonthly($range, $customStart, $customEnd, $returnObjects = false)
    {
        $dateEnd = Mage::app()->getLocale()->date();
        $dateStart = clone $dateEnd;

        // go to the end of a day
        $dateEnd->setHour(23)
            ->setMinute(59)
            ->setSecond(59);

        $dateStart->setHour(0)
            ->setMinute(0)
            ->setSecond(0);

        switch ($range) {
            case 'ytd':
                $dateStart->setDayOfYear(1);
                break;

            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
            case '10':
            case '11':
            case '12':
            case '13':
                $dateStart->subMonth($range)
                    ->setDay(Mage::getStoreConfig('reports/dashboard/mtd_start'));
                $dateEnd = clone $dateStart;
                $dateEnd->addMonth(1)
                    ->subDay(1);
                break;

            case 'lifetime':
                $dateStart->setYear(1970);
                break;
            default:
                break;
        }

        $dateStart = Varien_Date::formatDate($dateStart, true);
        $dateEnd = Varien_Date::formatDate($dateEnd, true);

        if ($returnObjects) {
            return array($dateStart, $dateEnd);
        } else {
            return array('from' => $dateStart, 'to' => $dateEnd, 'datetime' => true);
        }
    }

    public function getUpperTierInfo($email)
    {
        $collection = Mage::getModel('affiliateplus/account')->getCollection();
        $collection->addFieldToFilter('email', array('eq' => $email))
            ->getSelect()
            ->joinLeft(array('apt' => 'affiliatepluslevel_tier'), 'apt.tier_id = main_table.account_id', 'toptier_id')
            ->group('account_id');

        $item = $collection->getFirstItem();

        $uppertier = Mage::getModel('affiliateplus/account');
        if ($uppertier->load($item->getToptierId())) {
            return $uppertier;
        } else {
            return null;
        }
    }

    public function getDoctorFromOrderId($orderId)
    {
        if ($transaction = Mage::getModel('affiliateplus/transaction')->load($orderId, 'order_id')) {
            return Mage::getModel('admin/user')->load($transaction->getAccountEmail(), 'email');
        }

        return null;
    }
}
