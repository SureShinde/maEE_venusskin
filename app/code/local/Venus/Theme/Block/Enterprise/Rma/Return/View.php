<?php
class Venus_Theme_Block_Enterprise_Rma_Return_View extends Enterprise_Rma_Block_Return_View {
	public function getPrintShippingLabelButton() {
		return $this->getLayout()
		            ->createBlock('core/html_link')
		            ->setData(
			            array(
				            'href'  => $this->helper('enterprise_rma')->getPackagePopupUrlByRmaModel(
					            $this->getRma(),
					            'printlabel'
				            ),
				            'title' => Mage::helper('enterprise_rma')->__('Print Shipping Label'),
				            'class' => 'button button-dark',
			            )
		            )
		            ->setAnchorText(Mage::helper('enterprise_rma')->__('Print Shipping Label'))
		            ->toHtml();
	}

	public function getShowPackagesButton() {
		return $this->getLayout()
		            ->createBlock('core/html_link')
		            ->setData(
			            array(
				            'href'    => "javascript:void(0)",
				            'title'   => Mage::helper('enterprise_rma')->__('Show Packages'),
				            'onclick' => "popWin(
                        '" . $this->helper('enterprise_rma')->getPackagePopupUrlByRmaModel($this->getRma()) . "',
                        'package',
                        'width=800,height=600,top=0,left=0,resizable=yes,scrollbars=yes'); return false;",
				            'class'   => 'button button-dark',
			            )
		            )
		            ->setAnchorText(Mage::helper('enterprise_rma')->__('Show Packages'))
		            ->toHtml();
	}
}
