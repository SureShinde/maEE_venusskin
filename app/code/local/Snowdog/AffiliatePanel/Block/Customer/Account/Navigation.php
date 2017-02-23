<?php

class Snowdog_AffiliatePanel_Block_Customer_Account_Navigation extends Mage_Customer_Block_Account_Navigation
{
    public function addLink($name, $path, $label, $urlParams = array(), $onlyAffiliate = false)
    {
        if ((int)$onlyAffiliate === 1) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            if (!$customer->getFullAffiliate()) {
                unset($this->_links[$name]);
                return $this;
            }
        }
        $this->_links[$name] = new Varien_Object(array(
            'name' => $name,
            'path' => $path,
            'label' => $label,
            'url' => $this->getUrl($path, $urlParams),
        ));

        return $this;
    }
}