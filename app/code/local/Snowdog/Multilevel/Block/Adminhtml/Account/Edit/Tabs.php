<?php

/**
 * Class Snowdog_Multilevel_Block_Adminhtml_Account_Edit_Tabs
 */
class Snowdog_Multilevel_Block_Adminhtml_Account_Edit_Tabs extends Magestore_Affiliateplus_Block_Adminhtml_Account_Edit_Tabs
{
    protected function _beforeToHtml()
    {
        $id = $this->getRequest()->getParam('id');

        //event to add more tab
        Mage::dispatchEvent('affiliateplus_adminhtml_add_account_tab', array('form' => $this, 'id' => $id));

        $this->addTab('general_section', array(
            'label'     => Mage::helper('affiliateplus')->__('General Information'),
            'title'     => Mage::helper('affiliateplus')->__('General Information'),
            'content'   => $this->getLayout()->createBlock('affiliateplus/adminhtml_account_edit_tab_form')->toHtml(),
        ));

        $this->addTab('form_section', array(
            'label'     => Mage::helper('affiliateplus')->__('Payment Information'),
            'title'     => Mage::helper('affiliateplus')->__('Payment Information'),
            'content'   => $this->getLayout()->createBlock('affiliateplus/adminhtml_account_edit_tab_paymentinfo')->toHtml(),
        ));

        if($id){
            $this->addTab('transaction_section', array(
                'label'     => Mage::helper('affiliateplus')->__('History transaction'),
                'title'     => Mage::helper('affiliateplus')->__('History transaction'),
                'url'		=> $this->getUrl('*/*/transaction',array('_current'=>true)),
                'class'     => 'ajax',
            ));

            $this->addTab('payment_section', array(
                'label'     => Mage::helper('affiliateplus')->__('History Withdrawal'),
                'title'     => Mage::helper('affiliateplus')->__('History Withdrawal'),
                'url'		=> $this->getUrl('*/*/payment',array('_current'=>true)),
                'class'     => 'ajax',
            ));

            $this->addTab('subacounts', array(
                'label'     => Mage::helper('affiliateplus')->__('Subaccounts'),
                'title'     => Mage::helper('affiliateplus')->__('Subaccounts'),
                'content'   => $this->getLayout()->createBlock('multilevel/adminhtml_account_edit_tab_subaccounts')->toHtml()
            ));
        }

        $this->setActiveTab('general_section');
        return Mage_Adminhtml_Block_Widget_Tabs::_beforeToHtml();
    }
}
