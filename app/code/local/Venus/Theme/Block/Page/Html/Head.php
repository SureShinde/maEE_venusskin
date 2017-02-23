<?php
class Venus_Theme_Block_Page_Html_Head extends Mage_Page_Block_Html_Head {
	/**
	 * Merge static and skin files of the same format into 1 set of HEAD directives or even into 1 directive
	 *
	 * Will attempt to merge into 1 directive, if merging callback is provided. In this case it will generate
	 * filenames, rather than render urls.
	 * The merger callback is responsible for checking whether files exist, merging them and giving result URL
	 *
	 * @param string   $format      - HTML element format for sprintf('<element src="%s"%s />', $src, $params)
	 * @param array    $staticItems - array of relative names of static items to be grabbed from js/ folder
	 * @param array    $skinItems   - array of relative names of skin items to be found in skins according to design config
	 * @param callback $mergeCallback
	 *
	 * @return string
	 */
	protected function &_prepareStaticAndSkinElements($format, array $staticItems, array $skinItems, $mergeCallback = null) {
		$designPackage = Mage::getDesign();
		$baseJsUrl     = Mage::getBaseUrl('js');
		$items         = array();
		if ($mergeCallback && !is_callable($mergeCallback)) {
			$mergeCallback = null;
		}

		// get static files from the js folder, no need in lookups
		foreach ($staticItems as $params => $rows) {
			foreach ($rows as $name) {
				$filepath = Mage::getBaseDir() . DS . 'js' . DS . $name;
				if ($mergeCallback) {
					$items[$params][] = $filepath;
				} else {
					$items[$params][] = $baseJsUrl . $name . (strpos($name, '?') === false ? '?' : '&') . filemtime($filepath);
				}
			}
		}

		// lookup each file basing on current theme configuration
		foreach ($skinItems as $params => $rows) {
			foreach ($rows as $name) {
				$filepath = $designPackage->getFilename($name, array('_type' => 'skin'));
				if ($mergeCallback) {
					$items[$params][] = $filepath;
				} else {
					$items[$params][] = $designPackage->getSkinUrl($name, array()) . (strpos($name, '?') === false ? '?' : '&') . filemtime($filepath);
				}
			}
		}

		$html = '';
		foreach ($items as $params => $rows) {
			// attempt to merge
			$mergedUrl = false;
			if ($mergeCallback) {
				$mergedUrl = call_user_func($mergeCallback, $rows);
				if ($mergedUrl) {
					$filepath = str_replace(Mage::getBaseUrl('media'), Mage::getBaseDir('media') . DS, $mergedUrl);
					$mergedUrl .= (strpos($mergedUrl, '?') === false ? '?' : '&') . filemtime($filepath);
				}
			}

			// render elements
			$params = trim($params);
			$params = $params ? ' ' . $params : '';
			if ($mergedUrl) {
				$html .= sprintf($format, $mergedUrl, $params);
			} else {
				foreach ($rows as $src) {
					$html .= sprintf($format, $src, $params);
				}
			}
		}

		return $html;
	}
}
