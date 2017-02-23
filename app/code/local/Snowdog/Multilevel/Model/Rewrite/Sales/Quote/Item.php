<?php

/**
 * Class Snowdog_Multilevel_Model_Rewrite_Sales_Quote_Item
 */
class Snowdog_Multilevel_Model_Rewrite_Sales_Quote_Item extends Mage_Sales_Model_Quote_Item
{
    /**
     * @var array
     */
    protected $_notRepresentOptions = array(
        'info_buyRequest',
        'recurring_profile_options',
        'additional_options'
    );
}
