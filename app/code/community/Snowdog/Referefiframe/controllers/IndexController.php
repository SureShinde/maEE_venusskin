<?php

/**
 * Class Snowdog_Referefiframe_IndexController
 */
class Snowdog_Referefiframe_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Render iframe view
     */
    public function IndexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Redirect and save account code in session
     */
    public function redirectAction()
    {
        $request = $this->getRequest();
        $acc = $request->getParam('acc');
        $productId = $request->getParam('id');
        $product = Mage::getModel('catalog/product')->load($productId);
        $url = $product->getProductUrl();
        Mage::getSingleton('customer/session')->setAcc($acc);

        $this->_redirectUrl($url);
    }
}