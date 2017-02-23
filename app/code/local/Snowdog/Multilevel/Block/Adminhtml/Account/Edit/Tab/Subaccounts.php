<?php

class Snowdog_Multilevel_Block_Adminhtml_Account_Edit_Tab_Subaccounts extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $data = array();
        if (Mage::getSingleton('adminhtml/session')->getAccountData()) {
            $data = Mage::getSingleton('adminhtml/session')->getAccountData();
            Mage::getSingleton('adminhtml/session')->setAccountData(null);
        } elseif (Mage::registry('account_data')) {
            $data = Mage::registry('account_data')->getData();
        }

        $topTierId = null;
        $topTierId = Mage::helper('affiliatepluslevel')->getToptierIdByTierId($data['account_id']);
        $customerIds = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('full_affiliate', 1)->getAllIds();
        $accountCollection = Mage::getModel('affiliateplus/account')->getCollection()->addFieldToFilter('customer_id', array('in' => $customerIds));
        $values = array('' => '');

        foreach ($accountCollection as $account) {
            $values[$account->getId()] = $account->getName();
        }
        $fieldset = $form->addFieldset(
            'account_form',
            array(
                'legend' => Mage::helper('affiliateplus')->__('Subaccounts')
            )
        );
        $fieldset->addField(
            'tier_id',
            'hidden',
            array(
                'name' => 'tier_id',
                'value' => $data['account_id'],
            ));
        $fieldset->addField(
            'top_tire_id',
            'select',
            array(
                'name' => 'top_tire_id',
                'label' => Mage::helper('affiliateplus')->__('Top tire account'),
                'value' => $topTierId,
                'values' => $values,
                'note' => Mage::helper('affiliateplus')
                    ->__('For creating a new affiliate account which does not exist in customer database')
            )
        );

        return parent::_prepareForm();
    }
}
