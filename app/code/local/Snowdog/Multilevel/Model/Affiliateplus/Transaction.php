<?php

/**
 * Class Snowdog_Multilevel_Model_Affiliateplus_Transaction
 */
class Snowdog_Multilevel_Model_Affiliateplus_Transaction extends Magestore_Affiliateplus_Model_Transaction
{
    /**
     * Changed By Adam to solve the problem of invoice tung phan 20/08/2014
     * @return \Magestore_Affiliateplus_Model_Transaction
     */
    public function complete()
    {
        if ($this->canRestore()) {
            return $this;
        }
        if (!$this->getId()) {
            return $this;
        }

        if ($this->getStatus() == 3)
            return $this;

        $account = Mage::getModel('affiliateplus/account')
            ->setStoreId($this->getStoreId())
            ->load($this->getAccountId());

        if ($this->getStatus() != 1) {

            $additionalCommission = $this->getCommissionPlus() + $this->getCommission() * $this->getPercentPlus() / 100;
            try {
                if ($additionalCommission)
                    $account->setBalance($account->getData('balance') + $additionalCommission)->save();
            } catch (Exception $e) {

            }
        }

        $order = Mage::getModel('sales/order')->load($this->getOrderId());

        try {
            $commission = 0;
            $transactionCommission = 0;
            $transactionDiscount = 0;
            $configOrderStatus = $this->_getConfigHelper()->getCommissionConfig('updatebalance_orderstatus', 4);
            $configOrderStatus = $configOrderStatus ? $configOrderStatus : 'processing';

            if ($configOrderStatus == 'complete') {
                // Check if transaction is not completed
                if ($this->getStatus() != 1) {    // Changed By Adam to solve the problem
                    foreach ($order->getAllItems() as $item) {
                        if ($item->getAffiliateplusCommission()) {
                            $affiliateplusCommissionItem = explode(",", $item->getAffiliateplusCommissionItem());

                            $totalComs = array_sum($affiliateplusCommissionItem);
                            $firstComs = $affiliateplusCommissionItem[0];
                            $commission += $firstComs *
                                ($item->getQtyInvoiced() - $item->getQtyRefunded()) / $item->getQtyOrdered();
                            $transactionCommission += $totalComs *
                                ($item->getQtyInvoiced() - $item->getQtyRefunded()) / $item->getQtyOrdered();
                            $transactionDiscount += $item->getBaseAffiliateplusAmount() *
                                ($item->getQtyInvoiced() - $item->getQtyRefunded()) / $item->getQtyOrdered();
                        }
                        //update tier commission to tier affiliate when partial invoice
                        Mage::dispatchEvent(
                            'update_tiercommission_to_tieraffiliate_partial_invoice',
                            array('transaction' => $this, 'item' => $item, 'invoice_item' => '')
                        );
                    }
                }
            } else {
                foreach ($order->getAllItems() as $item) {
                    if ($item->getAffiliateplusCommission()) {
                        $collection = Mage::getModel('sales/order_invoice_item')->getCollection();
                        $collection->getSelect()
                            ->where('affiliateplus_commission_flag = 0')
                            ->where('order_item_id = ' . $item->getId());

                        $affiliateplusCommissionItem = explode(",", $item->getAffiliateplusCommissionItem());

                        $totalComs = array_sum($affiliateplusCommissionItem);
                        $firstComs = $affiliateplusCommissionItem[0];
                        if ($collection->getSize()) {


                            foreach ($collection as $invoiceItem) {
                                if ($invoiceItem && $invoiceItem->getId()) {
                                    $commission += $firstComs * $invoiceItem->getQty() / $item->getQtyOrdered();

                                    $invoiceItem->setAffiliateplusCommissionFlag(1)->save();

                                    //update tier commission to tier affiliate when partial invoice
                                    Mage::dispatchEvent(
                                        'update_tiercommission_to_tieraffiliate_partial_invoice',
                                        array('transaction' => $this, 'item' => $item, 'invoice_item' => $invoiceItem)
                                    );
                                }
                            }
                        }
                        // check if doesn't subtract commission from affiliate account balance when credit memo is created
                        if (!Mage::helper('affiliateplus/config')->getCommissionConfig(
                            'decrease_commission_creditmemo', $storeId)
                        ) {
                            $transactionCommission += $totalComs * ($item->getQtyInvoiced()) / $item->getQtyOrdered();
                            $transactionDiscount += $item->getBaseAffiliateplusAmount() *
                                ($item->getQtyInvoiced()) / $item->getQtyOrdered();
                        } else {
                            $transactionCommission += $totalComs *
                                ($item->getQtyInvoiced() - $item->getQtyRefunded()) / $item->getQtyOrdered();
                            $transactionDiscount += $item->getBaseAffiliateplusAmount() *
                                ($item->getQtyInvoiced() - $item->getQtyRefunded()) / $item->getQtyOrdered();
                        }
                    }
                }
            }
            if ($commission) {
                $status = 1;
                $account->setBalance($account->getData('balance') + $commission)
                    ->save();
                if ($transactionCommission) {
                    $this->setCommission($transactionCommission);
                }
                if ($transactionDiscount) {
                    if ($transactionDiscount <= 0)
                        $this->setDiscount(0);
                    else
                        $this->setDiscount(-$transactionDiscount);
                }
                $this->setStatus($status)->save();

                if ($transactionCommission) {

                    //update tiercommission to affiliatepluslevel_transaction table
                    Mage::dispatchEvent(
                        'update_tiercommission_to_transaction_partial_invoice',
                        array('transaction' => $this, 'order' => $order)
                    );

                    //Update commission to affiliateplusprogram_transaction table
                    Mage::dispatchEvent(
                        'update_commission_to_affiliateplusprogram_transaction_partial_invoice',
                        array('transaction' => $this, 'order' => $order)
                    );
                }

                $this->sendMailUpdatedTransactionToAccount(true);
            }

        } catch (Exception $e) {
            print_r($e->getMessage());
        }

        $this->completeTransaction();

        return $this;
    }

    public function completeTransaction()
    {
        $helper = Mage::helper('multilevel/data');
        $transaction = $this;

        /** @var Magestore_Affiliateplus_Model_Account $ownAccount */
        $ownAccount = Mage::getModel('affiliateplus/account')
            ->loadByCustomerId($transaction->getCustomerId());

        if (!$ownAccount->getHasPurchased()) {
            $ownAccount->setHasPurchased($helper::FIRST_PURCHASE_FLAG)->save();
            $transaction->setIsFirstPurchase($helper::FIRST_PURCHASE_FLAG)->save();
            $refererAccount = $helper->getRefererAccount($ownAccount->getCustomerId());
            if ($helper->isCustomer($ownAccount->getEmail())
                && $helper->isCustomer($refererAccount->getEmail())
                && $refererAccount->getCustomerId()
            ) {
                $amount = Mage::getStoreConfig($helper::XML_PATH_REFERRAL_PURCHASE_CREDIT_AMOUNT);
                $helper->saveBalance($amount, $refererAccount->getCustomerId());

            }
        }
        return $this;
    }
}
