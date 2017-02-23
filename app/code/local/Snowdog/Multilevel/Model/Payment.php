<?php

/**
 * @method int getAdminId()
 * @method int getOwnerId()
 */
class Snowdog_Multilevel_Model_Payment extends Magestore_Affiliateplus_Model_Payment
{
    public function apply() {
        $this->save();

        if (method_exists($this->getPayment(), 'apply')) {
            $this->getPayment()->apply($this);
        }

        return $this;
    }
}
