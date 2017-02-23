<?php
require_once 'abstract.php';

class Mage_Shell_Cache extends Mage_Shell_Abstract {
	public function run() {
		Mage::app()->addEventArea('adminhtml');

		if (isset($this->_args['enable']) || isset($this->_args['disable'])) {
			$types = array();
			foreach (Mage::app()->getCacheInstance()->getTypes() as $cacheType) {
				$types[$cacheType->getId()] = isset($this->_args['enable']) ? 1 : 0;
			}

			Mage::app()->saveUseCache($types);

			echo 'Caches have been ' . (isset($this->_args['enable']) ? 'enabled' : 'disabled') . "\n";
		} else if (isset($this->_args['refresh'])) {
			foreach (Mage::app()->getCacheInstance()->getTypes() as $cacheType) {
				Mage::app()->getCacheInstance()->cleanType($cacheType->getId());
				Mage::dispatchEvent('adminhtml_cache_refresh_type', array('type' => $cacheType->getId()));
			}

			echo "Caches have been refreshed\n";
		} else if (isset($this->_args['flush'])) {
			Mage::app()->cleanCache();
			Mage::dispatchEvent('adminhtml_cache_flush_system');

			echo "Caches have been flushed\n";
		} else if (isset($this->_args['flushstorage'])) {
			Mage::dispatchEvent('adminhtml_cache_flush_all');
			Mage::app()->getCacheInstance()->flush();

			echo "Cache storages have been flushed\n";
		} else {
			echo $this->usageHelp();
		}
	}

	public function usageHelp() {
		return <<<USAGE
Usage:  php -f cache.php -- [options]

  enable        Enable the cache
  disable       Disable the cache
  refresh       Refresh the cache
  flush         Flush Magento Cache
  flushstorage  Flush Cache Storage
  help          This help

USAGE;
	}
}

$shell = new Mage_Shell_Cache();
$shell->run();
