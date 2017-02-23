<?php
class Inspiratica_Git_IndexController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		$validAddr = array('207.97.227.253', '50.57.128.197', '108.171.174.178');
		if (in_array($_SERVER['REMOTE_ADDR'], $validAddr)) {
			$payload = json_decode($this->getRequest()->getParam('payload'), true);
			if ($payload && ((isset($_SERVER['MAGE_IS_DEVELOPER_MODE']) && $payload['repository']['master_branch'] == 'develop') || (!isset($_SERVER['MAGE_IS_DEVELOPER_MODE']) && $payload['repository']['master_branch'] == 'master'))) {
				file_put_contents(MAGENTO_ROOT . '/var/tmp/git_update', '1');
			}
			file_put_contents(MAGENTO_ROOT . '/var/log/git.log', print_r($payload, true), FILE_APPEND);
		} else {
			$this->norouteAction();
		}
	}
}
