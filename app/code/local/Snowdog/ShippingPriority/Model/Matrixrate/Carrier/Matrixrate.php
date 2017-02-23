<?php

class Snowdog_ShippingPriority_Model_Matrixrate_Carrier_Matrixrate extends Webshopapps_Matrixrate_Model_Carrier_Matrixrate
{

    /**
     * Enter description here...
     *
     * @param Mage_Shipping_Model_Rate_Request $data
     *
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        // exclude Virtual products price from Package value if pre-configured
        if (!$this->getConfigFlag('include_virtual_price') && $request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getProduct()->isVirtual() || $item->getProductType() == 'downloadable') {
                            $request->setPackageValue($request->getPackageValue() - $child->getBaseRowTotal());
                        }
                    }
                } elseif ($item->getProduct()->isVirtual() || $item->getProductType() == 'downloadable') {
                    $request->setPackageValue($request->getPackageValue() - $item->getBaseRowTotal());
                }
            }
        }

        // Free shipping by qty
        $freeQty = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeQty += $item->getQty() * ($child->getQty() -
                                    (is_numeric($child->getFreeShipping()) ?
                                        $child->getFreeShipping() : 0));
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeQty += ($item->getQty() - (is_numeric($item->getFreeShipping())
                            ? $item->getFreeShipping() : 0));
                }
            }
        }

        if (!$request->getMRConditionName()) {
            $request->setMRConditionName($this->getConfigData('condition_name')
                ? $this->getConfigData('condition_name') : $this->_default_condition_name);
        }

        // Package weight and qty free shipping
        $oldWeight = $request->getPackageWeight();
        $oldQty = $request->getPackageQty();

        if ($this->getConfigData('allow_free_shipping_promotions') && !$this->getConfigData('include_free_ship_items')) {
            $request->setPackageWeight($request->getFreeMethodWeight());
            $request->setPackageQty($oldQty - $freeQty);
        }

        $result = Mage::getModel('shipping/rate_result');
        $ratearray = $this->getRate($request);

        $request->setPackageWeight($oldWeight);
        $request->setPackageQty($oldQty);

        $freeShipping = false;

        if (is_numeric($this->getConfigData('free_shipping_threshold')) && $this->getConfigData('free_shipping_threshold') > 0 && $request->getPackageValue() > $this->getConfigData('free_shipping_threshold')) {
            $freeShipping = true;
        }
        if ($this->getConfigData('allow_free_shipping_promotions') && ($request->getFreeShipping() === true || $request->getPackageQty() == $this->getFreeBoxes())) {
            $freeShipping = true;
        }

        if ($freeShipping) {
            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier('matrixrate');
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod('matrixrate_free');
            $method->setPrice('0.00');
            $method->setMethodTitle($this->getConfigData('free_method_text'));
            $result->append($method);

            if ($this->getConfigData('show_only_free')) {
                return $result;
            }
        }

        $isAdminArea = Mage::app()->getStore()->isAdmin();
        foreach ($ratearray as $rate) {
            if (!empty($rate) && $rate['price'] >= 0) {
                if (isset($rate['hide_in_front'])  && $isAdminArea == false) {
                    if ($rate['hide_in_front'] == 1) {
                        continue;
                    }
                }
                $method = Mage::getModel('shipping/rate_result_method');

                $method->setCarrier('matrixrate');
                $method->setCarrierTitle($this->getConfigData('title'));

                $method->setMethod('matrixrate_' . $rate['pk']);

                $method->setMethodTitle(Mage::helper('matrixrate')->__($rate['delivery_type']));

                $shippingPrice = $this->getFinalPriceWithHandlingFee($rate['price']);
                $method->setCost($rate['cost']);
                $method->setDeliveryType($rate['delivery_type']);

                $method->setPrice($shippingPrice);

                $result->append($method);
            }
        }

        return $result;
    }
}