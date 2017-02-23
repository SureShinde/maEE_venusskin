<?php
class Venus_Theme_Block_Catalog_Product_List extends Mage_Catalog_Block_Product_List {
	const SHOW_TOP_TOOLBAR_DEFAULT    = true;
	const SHOW_BOTTOM_TOOLBAR_DEFAULT = true;

	protected $_showTopToolbar;
	protected $_showBottomToolbar;

	public function setShowTopToolbar($value) {
		$this->_showTopToolbar = $value;

		return $this;
	}

	public function getShowTopToolbar() {
		if ($this->_showTopToolbar === null) {
			$this->_showTopToolbar = self::SHOW_TOP_TOOLBAR_DEFAULT;
		}

		return $this->_showTopToolbar;
	}

	public function setShowBottomToolbar($value) {
		$this->_showBottomToolbar = $value;

		return $this;
	}

	public function getShowBottomToolbar() {
		if ($this->_showBottomToolbar === null) {
			$this->_showBottomToolbar = self::SHOW_BOTTOM_TOOLBAR_DEFAULT;
		}

		return $this->_showBottomToolbar;
	}
}
