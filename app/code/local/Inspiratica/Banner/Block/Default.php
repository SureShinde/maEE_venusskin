<?php
class Inspiratica_Banner_Block_Default extends Mage_Core_Block_Template {
	public function _prepareLayout() {
		return parent::_prepareLayout();
	}

	public function getBanner() {
		/*if (!$this->hasData('banner')) {
							$this->setData('banner', Mage::registry('banner'));
						}
						return $this->getData('banner');*/
	}

	public function setStoreBannerBlock($alias) {
		$this->setData("alias", Mage::app()->getStore()->getCode() . '_' . $alias);

		return $this;
	}

	public function setBannerBlock($alias) {
		$this->setData("alias", $alias);

		return $this;
	}

	public function getBlockData() {
		if (!$this->hasData('block_data')) {
			$alias = $this->getData("alias");

			$banner_block = Mage::getSingleton("banner/block")->load($alias, 'alias');
			$block_data   = $banner_block->getData();
			$banners      = Mage::getResourceModel("banner/banner")->getListBannerOfBlock($block_data);

			$result            = array();
			$result['block']   = $block_data;
			$result['banners'] = $banners;

			$this->setData('block_data', $result);
		}

		return $this->getData('block_data');
	}


	public function generateBannerCode($target, $banner) {
		$html       = '';
		$image_path = Mage::getBaseDir('media') . DS . 'banners' . DS . $banner['image'];
		if (file_exists($image_path)) {
			$html .= '<a href="' . $this->getUrl('banner/index/click', array('id' => $banner['id'])) . '" ' . ($target == 'NEW' ? 'target="_blank"' : '') . '>';
			$html .= '<img src="' . Mage::getBaseUrl('media') . 'banners/' . $banner['image'] . '" alt="' . $this->escapeHtml($banner['alt']) . '" />';
			$html .= '</a>';
		}

		return $html;
	}
}
