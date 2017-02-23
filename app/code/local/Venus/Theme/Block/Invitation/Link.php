<?php

class Venus_Theme_Block_Invitation_Link extends Enterprise_Invitation_Block_Link
{
    /**
     * Adding link to account links block link params if invitation
     * is allowed globaly and for current website
     *
     * @param string $block
     * @param string $name
     * @param string $path
     * @param string $label
     * @param array $urlParams
     * @return Enterprise_Invitation_Block_Customer_Link
     */
    public function addDashboardLink($block, $name, $path, $label, $urlParams = array())
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();

        if (Mage::getSingleton('enterprise_invitation/config')->isEnabledOnFront()
            && !$customer->getFullAffiliate()
        ) {
            $blockInstance = $this->getLayout()->getBlock($block);
            if ($blockInstance) {
                $blockInstance->addLink($name, $path, $label, $urlParams);
            }
        }
        return $this;
    }
}