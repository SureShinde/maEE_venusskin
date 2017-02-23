<?php

/**
 * Class Snowdog_Multilevel_Model_Affiliateplus_Observer
 */
class Snowdog_Multilevel_Model_Affiliateplus_Observer extends Magestore_Affiliateplus_Model_Observer
{
    public function orderPlaceAfter($observer)
    {
        /**
         * @var Varien_Event_Observer $observer
         * @var Mage_Sales_Model_Order $order
         */

        $order = $observer->getEvent()->getOrder();

        if ($this->_getSession()->getData('affiliateplus_order_placed_' . $order->getId())) {
            return $this;
        }

        $this->_getSession()->setData('affiliateplus_order_placed_' . $order->getId(), true);

        if ($baseAmount = $order->getBaseAffiliateCredit()) {
            Mage::throwException('HOW DID I GET HERE?');

            $session = Mage::getSingleton('checkout/session');
            $session->setUseAffiliateCredit('');
            $session->setAffiliateCredit(0);

            $account = Mage::getSingleton('affiliateplus/session')->getAccount();
            $payment = Mage::getModel('affiliateplus/payment')
                ->setPaymentMethod('credit')
                ->setAmount(-1 * $baseAmount)
                ->setAccountId($account->getId())
                ->setAccountName($account->getName())
                ->setAccountEmail($account->getEmail())
                ->setRequestTime(now())
                ->setStatus(3)
                ->setIsRequest(1)
                ->setIsPayerFee(0)
                ->setData('is_created_by_recurring', 1)
                ->setData('is_refund_balance', 1);

            if (Mage::helper('affiliateplus/config')->getSharingConfig('balance') == 'store') {
                $payment->setStoreIds($order->getStoreId());
            }

            $paymentMethod = $payment->getPayment();
            $paymentMethod->addData(
                array(
                    'order_id' => $order->getId(),
                    'order_increment_id' => $order->getIncrementId(),
                    'base_paid_amount' => -$baseAmount,
                    'paid_amount' => -$order->getAffiliateCredit(),
                )
            );

            try {
                $payment->save();
                $paymentMethod->savePaymentMethodInfo();
            } catch (Exception $e) {
                // Ignore
            }
        }

        if (!$order->getBaseSubtotal()) {
            return $this;
        }

        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        $affiliateId = Mage::getModel('affiliateplus/account')->loadByCustomerId($customer->getId())->getId();

        $topTierId = Mage::getSingleton('multilevel/tier')->getTopMostTierId($affiliateId);
        $account = Mage::getModel('affiliateplus/account')->setStoreId($order->getStoreId())->load($topTierId);

        $accounts = array($account);

        if ($pemAccount = Mage::getSingleton('multilevel/customer')->getAffiliatePemAccount($customer)) {
            $accounts[] = $pemAccount;
        }

        foreach ($accounts as $accountIndex => $account) {
            if ($account && $account->getId()) {
                $affiliateInfo = array(
                    $account->getIdentifyCode() => array(
                        'index' => 1,
                        'code' => $account->getIdentifyCode(),
                        'account' => $account,
                        'tier_id' => $affiliateId
                    )
                );

                if ($this->_getConfigHelper()->getCommissionConfig('life_time_sales')) {
                    $tracksCollection = Mage::getResourceModel('affiliateplus/tracking_collection');

                    if ($order->getCustomerId()) {
                        $tracksCollection->getSelect()->where(
                            'customer_id = ' . $order->getCustomerId()
                            . ' OR customer_email = ?', $order->getCustomerEmail()
                        );
                    } else {
                        $tracksCollection->addFieldToFilter('customer_email', $order->getCustomerEmail());
                    }

                    if (!$tracksCollection->getSize()) {
                        try {
                            Mage::getModel('affiliateplus/tracking')->setData(
                                array(
                                    'account_id' => $account->getId(),
                                    'customer_id' => $order->getCustomerId(),
                                    'customer_email' => $order->getCustomerEmail(),
                                    'created_time' => now()
                                )
                            )->save();
                        } catch (Exception $e) {
                            // Ignore
                        }
                    }
                }

                $baseDiscount = $order->getBaseAffiliateplusDiscount();

                $commissionObj = new Varien_Object(
                    array(
                        'commission' => 0,
                        'default_commission' => true,
                        'order_item_ids' => array(),
                        'order_item_names' => array(),
                        'commission_items' => array(),
                        'extra_content' => array(),
                        'tier_commissions' => array(),
                    )
                );

                Mage::dispatchEvent(
                    'affiliateplus_calculate_commission_before',
                    array(
                        'order' => $order,
                        'affiliate_info' => $affiliateInfo,
                        'commission_obj' => $commissionObj,
                    )
                );

                $commissionType = $this->_getConfigHelper()->getCommissionConfig('commission_type');
                $commissionValue = floatval($this->_getConfigHelper()->getCommissionConfig('commission'));

                if (Mage::helper('affiliateplus/cookie')->getNumberOrdered()) {
                    if ($this->_getConfigHelper()->getCommissionConfig('use_secondary')) {
                        $commissionType = $this->_getConfigHelper()->getCommissionConfig('secondary_type');
                        $commissionValue = floatval(
                            $this->_getConfigHelper()->getCommissionConfig('secondary_commission')
                        );
                    }
                }

                $commission = $commissionObj->getCommission();
                $orderItemIds = $commissionObj->getOrderItemIds();
                $orderItemNames = $commissionObj->getOrderItemNames();
                $commissionItems = $commissionObj->getCommissionItems();
                $extraContent = $commissionObj->getExtraContent();
                $tierCommissions = $commissionObj->getTierCommissions();

                $defaultItemIds = array();
                $defaultItemNames = array();
                $defaultAmount = 0;
                $defCommission = 0;

                $baseItemsPrice = 0;
                foreach ($order->getAllItems() as $item) {
                    if ($item->getParentItemId()) {
                        continue;
                    }

                    // Kiem tra xem item da tinh trong program nao chua, neu roi thi ko tinh nua
                    if (in_array($item->getId(), $commissionItems)) {
                        continue;
                    }
                    if ($item->getHasChildren() && $item->isChildrenCalculated()) {

                        foreach ($item->getChildrenItems() as $child) {
                            $baseItemsPrice += $item->getQtyOrdered() *
                                ($child->getQtyOrdered() * $child->getBasePrice()
                                    - $child->getBaseDiscountAmount() -
                                    $child->getBaseAffiliateplusAmount());
                            //$baseItemsPrice += $item->getQtyOrdered() * ($child->getQty() * $child->getBasePrice() - $child->getBaseDiscountAmount() - $child->getBaseAffiliateplusAmount());
                        }
                    } elseif ($item->getProduct()) {

                        $baseItemsPrice += $item->getQtyOrdered()
                            * $item->getBasePrice() - $item->getBaseDiscountAmount()
                            - $item->getBaseAffiliateplusAmount();
                    }
                }


                if ($commissionValue && $commissionObj->getDefaultCommission()) {
                    if ($commissionType == 'percentage') {
                        if ($commissionValue > 100) {
                            $commissionValue = 100;
                        }

                        if ($commissionValue < 0) {
                            $commissionValue = 0;
                        }
                    }

                    foreach ($order->getAllItems() as $item) {
                        $affiliateplusCommissionItem = '';
                        if ($item->getParentItemId()) {
                            continue;
                        }

                        if (in_array($item->getId(), $commissionItems)) {
                            continue;
                        }

                        if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                            foreach ($item->getChildrenItems() as $child) {
                                if ($this->_getConfigHelper()->getCommissionConfig('affiliate_type') == 'profit') {
                                    $baseProfit = $child->getBasePrice() - $child->getBaseCost();
                                } else {
                                    $baseProfit = $child->getBasePrice();
                                }

                                $baseProfit = $child->getQtyOrdered()
                                    * $baseProfit - $child->getBaseDiscountAmount()
                                    - $child->getBaseAffiliateplusAmount();

                                if ($baseProfit <= 0) {
                                    continue;
                                }

                                // $childHasCommission = true;
                                /* Changed By Adam: Commission for whole cart 22/07/2014 */
                                if ($commissionType == "cart_fixed") {
                                    $commissionValue = min($commissionValue, $baseItemsPrice);
                                    $itemPrice = $child->getQtyOrdered() * $child->getBasePrice()
                                        - $child->getBaseDiscountAmount() - $child->getBaseAffiliateplusAmount();
                                    $itemCommission = $itemPrice * $commissionValue / $baseItemsPrice;
                                    $defaultCommission = min($itemPrice * $commissionValue / $baseItemsPrice, $baseProfit);
                                } elseif ($commissionType == 'fixed') {
                                    $defaultCommission = min($child->getQtyOrdered() * $commissionValue, $baseProfit);
                                } elseif ($commissionType == 'percentage') {
                                    $defaultCommission = $baseProfit * $commissionValue / 100;
                                }
                                // Changed By Adam 14/08/2014: Invoice tung phan
                                $affiliateplusCommissionItem .= $defaultCommission . ",";
                                $commissionObj = new Varien_Object(
                                    array(
                                        'profit' => $baseProfit,
                                        'commission' => $defaultCommission,
                                        'tier_commission' => array(),
                                        'base_item_price' => $baseItemsPrice, // Added By Adam 22/07/2014
                                        'affiliateplus_commission_item' => $affiliateplusCommissionItem     // Added By Adam 14/08/2014
                                    )
                                );

                                Mage::dispatchEvent(
                                    'affiliateplus_calculate_tier_commission',
                                    array(
                                        'item' => $child,
                                        'account' => $account,
                                        'commission_obj' => $commissionObj
                                    )
                                );

                                if ($commissionObj->getTierCommission()) {
                                    $tierCommissions[$child->getId()] = $commissionObj->getTierCommission();
                                }

                                $commission += $commissionObj->getCommission();
                                $child->setAffiliateplusCommission($commissionObj->getCommission());

                                $defCommission += $commissionObj->getCommission();
                                $defaultAmount += $child->getBasePrice();

                                $orderItemIds[] = $child->getProduct()->getId();
                                $orderItemNames[] = $child->getName();

                                $defaultItemIds[] = $child->getProduct()->getId();
                                $defaultItemNames[] = $child->getName();
                            }
                        } else {
                            if ($this->_getConfigHelper()->getCommissionConfig('affiliate_type') == 'profit') {
                                $baseProfit = $item->getBasePrice() - $item->getBaseCost();
                            } else {
                                $baseProfit = $item->getBasePrice();
                            }

                            $baseProfit = $item->getQtyOrdered() * $baseProfit
                                - $item->getBaseDiscountAmount() - $item->getBaseAffiliateplusAmount();

                            if ($baseProfit <= 0) {
                                continue;
                            }

                            $orderItemIds[] = $item->getProduct()->getId();
                            $orderItemNames[] = $item->getName();

                            $defaultItemIds[] = $item->getProduct()->getId();
                            $defaultItemNames[] = $item->getName();

                            /* Changed BY Adam 22/07/2014 */
                            if ($commissionType == 'cart_fixed') {
                                $commissionValue = min($commissionValue, $baseItemsPrice);
                                $itemPrice = $item->getQtyOrdered() * $item->getBasePrice()
                                    - $item->getBaseDiscountAmount() - $item->getBaseAffiliateplusAmount();
                                $itemCommission = $itemPrice * $commissionValue / $baseItemsPrice;
                                $defaultCommission = min($itemPrice * $commissionValue / $baseItemsPrice, $baseProfit);
                            } elseif ($commissionType == 'fixed') {
                                $defaultCommission = min($item->getQtyOrdered() * $commissionValue, $baseProfit);
                            } elseif ($commissionType == 'percentage') {
                                $defaultCommission = $baseProfit * $commissionValue / 100;
                            }

                            $affiliateplusCommissionItem .= $defaultCommission . ",";

                            $commissionObj = new Varien_Object(
                                array(
                                    'profit' => $baseProfit,
                                    'commission' => $defaultCommission,
                                    'tier_commission' => array(),
                                    'base_item_price' => $baseItemsPrice, // Added By Adam 22/07/2014
                                    'affiliateplus_commission_item' => $affiliateplusCommissionItem,     // Added By Adam 14/08/2014
                                )
                            );

                            Mage::dispatchEvent(
                                'affiliateplus_calculate_tier_commission',
                                array(
                                    'item' => $item,
                                    'account' => $account,
                                    'commission_obj' => $commissionObj
                                )
                            );

                            if ($commissionObj->getTierCommission()) {
                                $tierCommissions[$item->getId()] = $commissionObj->getTierCommission();
                            }

                            $commission += $commissionObj->getCommission();
                            $item->setAffiliateplusCommission($commissionObj->getCommission());

                            // Changed By Adam 14/08/2014: Invoice tung phan
                            $item->setAffiliateplusCommissionItem($commissionObj->getAffiliateplusCommissionItem());

                            $defCommission += $commissionObj->getCommission();
                            $defaultAmount += $item->getBasePrice();
                        }

                        if ($item->getProduct()->getPhysiciansOnly()) {
                            $commission = 0;
                            $tierCommissions = 0;
                            $defaultCommission = 0;
                        }
                    }
                }

                if (!$baseDiscount && !$commission) {
                    return $this;
                }

                $transactionData = array(
                    'account_id' => $account->getId(),
                    'account_name' => $account->getName(),
                    'account_email' => $account->getEmail(),
                    'customer_id' => $order->getCustomerId(), // $customer->getId(),
                    'customer_email' => $order->getCustomerEmail(), // $customer->getEmail(),
                    'order_id' => $order->getId(),
                    'order_number' => $order->getIncrementId(),
                    'order_item_ids' => implode(',', $orderItemIds),
                    'order_item_names' => implode(',', $orderItemNames),
                    'total_amount' => $order->getBaseSubtotal(),
                    'discount' => $baseDiscount,
                    'commission' => $commission,
                    'created_time' => now(),
                    'status' => '2',
                    'store_id' => $order->getStoreId(),
                    'extra_content' => $extraContent,
                    'tier_commissions' => $tierCommissions,
                    'default_item_ids' => $defaultItemIds,
                    'default_item_names' => $defaultItemNames,
                    'default_commission' => $defCommission,
                    'default_amount' => $defaultAmount,
                    'type' => 3,
                    'toptier_id' => $accountIndex == 0 ? Mage::helper('affiliatepluslevel')
                        ->getToptierIdByTierId($affiliateId) : $account->getId()
                );

                if ($account->getUsingCoupon()) {
                    $session = Mage::getSingleton('checkout/session');

                    $transactionData['coupon_code'] = $session->getData('affiliate_coupon_code');

                    if ($program = $account->getUsingProgram()) {
                        $transactionData['program_id'] = $program->getId();
                        $transactionData['program_name'] = $program->getName();
                    } else {
                        $transactionData['program_id'] = 0;
                        $transactionData['program_name'] = 'Affiliate Program';
                    }

                    $session->unsetData('affiliate_coupon_code');
                    $session->unsetData('affiliate_coupon_data');
                }

                $transaction = Mage::getModel('affiliateplus/transaction')->setData($transactionData)->setId(null);

                Mage::dispatchEvent(
                    'affiliateplus_calculate_commission_after',
                    array(
                        'transaction' => $transaction,
                        'order' => $order,
                        'affiliate_info' => $affiliateInfo,
                    )
                );

                try {
                    $transaction->save();

                    Mage::dispatchEvent(
                        'affiliateplus_recalculate_commission',
                        array(
                            'transaction' => $transaction,
                            'order' => $order,
                            'affiliate_info' => $affiliateInfo,
                        )
                    );

                    if ($transaction->getIsChangedData()) {
                        $transaction->save();
                    }

                    Mage::dispatchEvent(
                        'affiliateplus_created_transaction',
                        array(
                            'transaction' => $transaction,
                            'order' => $order,
                            'affiliate_info' => $affiliateInfo,
                        )
                    );

                    $transaction->sendMailNewTransactionToAccount();
                    $transaction->sendMailNewTransactionToSales();
                } catch (Exception $e) {
                    // Exception
                }
            }
        }

        return $this;
    }

    /**
     * @return Mage_Core_Model_Session_Abstract
     */
    protected function _getSession()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return Mage::getSingleton('adminhtml/session');
        }

        return Mage::getSingleton('core/session');
    }

    protected function _getCustomer()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return Mage::getSingleton('adminhtml/session_quote')->getCustomer();
        }

        return Mage::getSingleton('customer/session')->getCustomer();
    }

    public function disableCache($observer)
    {
    }
}


