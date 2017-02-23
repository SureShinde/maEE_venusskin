<?php
class Inspiratica_Banner_Block_Banner extends Mage_Core_Block_Template {
	public function returnBlock($blockposition, $alias) {
		$banners = array();
		foreach ($alias as $block_name) {
			$banner_block  = Mage::getSingleton("banner/block")->load($block_name, 'alias');
			$block         = $banner_block->getData();
			$checkposition = $block['block_position'];
			$banner        = $this->getLayout()->createBlock('banner/default')->setTemplate('banner/banner.phtml')->renderView();
			if ($blockposition == $checkposition) {
				$banners[] = $banner;
			}
		}

		return $banners;
	}

	protected function _toHtml() {
		$block_collection = Mage::getModel("banner/block")
			->getCollection()
			->addFieldToFilter('status', 1)
			->addFieldToFilter('block_position', $this->getBlockPosition());

		$alias = array();
		foreach ($block_collection as $block) {
			$alias[] = $block->getAlias();
		}

		return implode(
			'', $this->returnBlock(
				  $this->getBlockPosition(),
				  $alias
			  )
		);
	}
}
