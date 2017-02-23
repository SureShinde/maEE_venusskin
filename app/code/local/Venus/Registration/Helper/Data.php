<?php
class Venus_Registration_Helper_Data extends Mage_Core_Helper_Abstract {
	const XML_PATH_DEFAULT_CLINIC_ROLE_ID    = 'admin/physician_account/default_id';
	const NEW_CLINIC_ACCOUNT_ROLE_TREE_LEVEL = 2;

	function generatePassword($length = 8) {
		$length = $length < 8 ? 8 : $length;
		$chars  = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		return substr(str_shuffle($chars), 0, $length);
	}
}
