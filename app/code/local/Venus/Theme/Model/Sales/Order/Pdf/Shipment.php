<?php

/**
 * Class Venus_Theme_Model_Sales_Order_Pdf_Shipment
 */
class Venus_Theme_Model_Sales_Order_Pdf_Shipment extends Mage_Sales_Model_Order_Pdf_Shipment
{
    /**
     * Draw shipment header
     *
     * @param Zend_Pdf_Page $page
     */
    protected function _drawHeader(Zend_Pdf_Page $page)
    {
        $this->_setFontRegular($page, 10);
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $this->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));

        //columns headers
        $header[0][] = array(
            'text' => Mage::helper('sales')->__('Order Shipment Summary:'),
            'feed' => 30,
        );

        $gridHeader = array(
            'lines' => $header,
            'height' => 5
        );

        $this->drawLineBlocks($page, array($gridHeader), array('table_header' => true));
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;

        /* Add table head */
        $this->_setFontRegular($page, 8);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y - 15);
        $this->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));

        //columns headers
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Part Number'),
            'feed' => 30,
        );
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Products'),
            'feed' => 120,
        );
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Qty Ord'),
            'feed' => 420
        );
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Qty Ship'),
            'feed' => 470
        );
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Qty Back'),
            'feed' => 520
        );

        $lineBlock = array(
            'lines' => $lines,
            'height' => 10
        );

        $this->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }

    protected function _drawItem(Varien_Object $item, Zend_Pdf_Page $page, Mage_Sales_Model_Order $order, $shippment = false)
    {
        if($item instanceof Mage_Sales_Model_Order_Item) {
            $orderItem = $item;
        }else{
            $orderItem = $item->getOrderItem();
        }
        $type = $orderItem->getProductType();
        $renderer = $this->_getRenderer($type);

        $this->renderItem($item, $page, $order, $renderer, $shippment);

        $transportObject = new Varien_Object(array('renderer_type_list' => array()));
        Mage::dispatchEvent('pdf_item_draw_after', array(
            'transport_object' => $transportObject,
            'entity_item' => $item
        ));

        foreach ($transportObject->getRendererTypeList() as $type) {
            $renderer = $this->_getRenderer($type);
            if ($renderer) {
                $this->renderItem($orderItem, $page, $order, $renderer, $shippment);
            }
        }

        return $renderer->getPage();
    }

    /**
     * Draw grid header
     *
     * @param $page
     * @return mixed
     */
    protected function _drawGridShippmentGrid($page)
    {
        $this->_setFontRegular($page, 10);
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $this->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));

        //columns headers
        $header[0][] = array(
            'text' => Mage::helper('sales')->__('Products In This Shipment:'),
            'feed' => 30,
        );

        $gridHeader = array(
            'lines' => $header,
            'height' => 5
        );

        $this->drawLineBlocks($page, array($gridHeader), array('table_header' => true));
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;

        $this->_setFontRegular($page, 8);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y - 15);
        $this->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));

        //columns headers
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Qty'),
            'feed' => 30
        );
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Products'),
            'feed' => 90,
        );
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Weight'),
            'feed' => 430
        );
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('SKU'),
            'feed' => 530,
        );

        $lineBlock = array(
            'lines' => $lines,
            'height' => 10
        );

        $this->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;

        return $page;
    }

    /**
     * Return PDF document
     *
     * @param  array $shipments
     * @return Zend_Pdf
     */
    public function getPdf($shipments = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('shipment');
        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
        foreach ($shipments as $shipment) {
            if ($shipment->getStoreId()) {
                Mage::app()->getLocale()->emulate($shipment->getStoreId());
                Mage::app()->setCurrentStore($shipment->getStoreId());
            }
            $page = $this->newPage();
            $order = $shipment->getOrder();
            /* Add image */
            $this->insertLogo($page, $shipment->getStore());
            /* Add address */
            $this->insertAddress($page, $shipment->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $shipment,
                Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_SHIPMENT_PUT_ORDER_ID, $order->getStoreId())
            );
            /* Add document text and number */
            $this->insertDocumentNumber(
                $page,
                Mage::helper('sales')->__('Packingslip # ') . $shipment->getIncrementId()
            );
            /* Add table */
            $this->_drawGridShippmentGrid($page);
            /* Add body */
            foreach ($shipment->getAllItems() as $item) {
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order, true);
                $page = end($pdf->pages);
            }

            $summaryItem = $this->_getOrderItems($shipments);
            if ($summaryItem) {
                $this->_drawHeader($page);
                foreach ($summaryItem as $item) {
                    /* Draw item */
                    $this->_drawItem($item, $page, $order);
                    $page = end($pdf->pages);
                }
            }
        }
        $this->_afterGetPdf();
        if ($shipment->getStoreId()) {
            Mage::app()->getLocale()->revert();
        }
        return $pdf;
    }

    /**
     * Render item
     *
     * @param Varien_Object $item
     * @param Zend_Pdf_Page $page
     * @param Mage_Sales_Model_Order $order
     * @param Mage_Sales_Model_Order_Pdf_Items_Abstract $renderer
     *
     * @return Mage_Sales_Model_Order_Pdf_Abstract
     */
    public function renderItem(Varien_Object $item, Zend_Pdf_Page $page, Mage_Sales_Model_Order $order, $renderer, $shippment)
    {
        $renderer->setOrder($order)
            ->setItem($item)
            ->setPdf($this)
            ->setPage($page)
            ->setRenderedModel($this);
        if ($shippment) {
            $renderer->drawShippment();
        } else {
            $renderer->draw();
        }

        return $this;
    }

    /**
     * Get all ordered item
     *
     * @param $shipments
     * @return null
     */
    protected function _getOrderItems($shipments)
    {
        if (isset($shipments[0])) {
            $orderId = $shipments[0]->getOrderId();

            return Mage::getResourceModel('sales/order_item_collection')
                ->addFieldToFilter('order_id', $orderId);

        }

        return null;
    }
}
