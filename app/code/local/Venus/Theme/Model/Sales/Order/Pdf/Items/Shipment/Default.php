<?php

/**
 * Class Venus_Theme_Model_Sales_Order_Pdf_Items_Shipment_Default
 */
class Venus_Theme_Model_Sales_Order_Pdf_Items_Shipment_Default extends Mage_Sales_Model_Order_Pdf_Items_Shipment_Default
{
    protected $_productResourceModel;

    /**
     * Draw pdf row
     */
    public function draw()
    {
        /**
         * @var Mage_Sales_Model_Order_Item $item
         */
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();
        $partNumber = $this->_getPartNumber($item->getProductId(), $item->getStoreId());
        $lines = array();

        $lines[0] = array(
            array(
                'text' => $partNumber,
                'feed' => 30,
            ),
            array(
                'text' => Mage::helper('core/string')->str_split($item->getName(), 60, true, true),
                'feed' => 120,
            ),
            array(
                'text' => $item->getQtyOrdered() * 1,
                'feed' => 420,
            ),
            array(
                'text' => $item->getQtyShipped() * 1,
                'feed' => 470,
            ),
            array(
                'text' => ($item->getQtyOrdered() - $item->getQtyShipped()) * 1,
                'feed' => 520,
            )
        );


        $lineBlock = array(
            'lines' => $lines,
            'height' => 20
        );

        $page = $pdf->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $this->setPage($page);
    }

    /**
     * Draw shippment pdf row
     */
    public function drawShippment()
    {
        /**
         * @var Mage_Sales_Model_Order_Shipment_Item $item
         */
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();
        $lines = array();
        $description = trim(Mage::getResourceSingleton('catalog/product')
            ->getAttributeRawValue(
                $item->getProductId(),
                'pdf_description',
                $item->getShipment()->getStoreId()
            )
        );

        $lines[0] = array(
            array(
                'text' => $item->getQty() * 1,
                'feed' => 30,
            ),
            array(
                'text' => Mage::helper('core/string')->str_split($item->getName(), 60, true, true),
                'feed' => 90,
            ),
            array(
                'text' => $item->getWeight(),
                'feed' => 430,
            ),
            array(
                'text' => Mage::helper('core/string')->str_split($this->getSku($item), 25),
                'feed' => 460,
            )
        );

        if (!empty($description)) {
            foreach (explode("\n", $description) as $descriptionLine) {
                $descriptionLine = trim($descriptionLine);
                $lines[][] = array(
                    'text' => Mage::helper('core/string')->str_split($descriptionLine, 100, true, true),
                    'feed' => 100
                );
            }
        }

        // Custom options
        $options = $this->getItemOptions();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = array(
                    'text' => Mage::helper('core/string')->str_split(strip_tags($option['label']), 70, true, true),
                    'font' => 'italic',
                    'feed' => 110
                );

                // draw options value
                if ($option['value']) {
                    $_printValue = isset($option['print_value']) ? $option['print_value'] : strip_tags($option['value']);
                    $values = explode(', ', $_printValue);
                    foreach ($values as $value) {
                        $lines[][] = array(
                            'text' => Mage::helper('core/string')->str_split($value, 50, true, true),
                            'feed' => 115
                        );
                    }
                }
            }
        }

        $lineBlock = array(
            'lines' => $lines,
            'height' => 20
        );

        $page = $pdf->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $this->setPage($page);
    }

    protected function _getPartNumber($productId, $storeId)
    {
        if (!$this->_productResourceModel) {
            $this->_productResourceModel = Mage::getResourceModel('catalog/product');
        }

        return $this->_productResourceModel->getAttributeRawValue(
            $productId,
            'inventory_part_number',
            $storeId
        );
    }
}
