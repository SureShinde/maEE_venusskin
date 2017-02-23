<?php

class Venus_Theme_Block_Adminhtml_Dashboard_Grids extends Mage_Adminhtml_Block_Dashboard_Grids
{
    const ADMIN_ROLE_ID = 1;

    protected function _prepareLayout()
    {
        $this->addTab(
            'reviewed_products', array(
                'label' => $this->__('Most Viewed Products'),
                'content' => $this->getLayout()->createBlock('adminhtml/dashboard_tab_products_viewed')->toHtml()
            )
        );

        $this->addTab('new_customers', array(
            'label' => $this->__('New Patients'),
            'content' => $this->getLayout()->createBlock('adminhtml/dashboard_tab_customers_newest')->toHtml()
        ));

        $this->addTab(
            'most_customers', array(
                'label' => $this->__('Most Active Patients'),
                'content' => $this->getLayout()->createBlock('adminhtml/dashboard_tab_customers_most')->toHtml()
            )
        );

        $this->addTab(
            'least_customers', array(
                'label' => $this->__('Least Active Patients'),
                'content' => $this->getLayout()->createBlock('adminhtml/dashboard_tab_customers_least')->toHtml()
            )
        );

        $this->addTab(
            'inactive_customers', array(
                'label' => $this->__('Inactive Patients'),
                'content' => $this->getLayout()->createBlock('adminhtml/dashboard_tab_customers_inactive')->toHtml()
            )
        );

        $adminUser = Mage::getSingleton('admin/session')->getUser();
        $role = $adminUser->getRole();
        $topLabel = $this->__('Top Performing %s', $role->getRoleId() == self::ADMIN_ROLE_ID || $role->getRoleId() == Mage::getStoreConfig(Venus_Multilevel_Model_Admin_Role_Pem::XML_PATH_PEM_ROLE_ID) ? 'Clinics' : 'Team Members');
        $underLabel = $this->__('Underperforming %s', $role->getRoleId() == self::ADMIN_ROLE_ID || $role->getRoleId() == Mage::getStoreConfig(Venus_Multilevel_Model_Admin_Role_Pem::XML_PATH_PEM_ROLE_ID) ? 'Clinics' : 'Team Members');

        $this->addTab(
            'top_clinics', array(
                'label' => $topLabel,
                'content' => $this->getLayout()->createBlock('adminhtml/dashboard_tab_clinics_top')->toHtml()
            )
        );

        $this->addTab(
            'under_clinics', array(
                'label' => $underLabel,
                'content' => $this->getLayout()->createBlock('adminhtml/dashboard_tab_clinics_under')->toHtml()
            )
        );

        return $this;
    }
}
