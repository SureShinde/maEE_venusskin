<?php
class Inspiratica_Banner_Helper_Data extends Mage_Core_Helper_Abstract {
	public static function uploadBannerImage() {
		Mage::helper('banner')->createImageFolder();
		$banner_image_path = Mage::getBaseDir('media') . DS . 'banners' . DS;
		$image             = "";
		if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
			try {
				$uploader = new Varien_File_Uploader('image');
				$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
				$uploader->setAllowRenameFiles(false);
				$uploader->setFilesDispersion(false);
				$uploader->save($banner_image_path, $_FILES['image']['name']);
			} catch (Exception $e) {
			}
			$image = $_FILES['image']['name'];
		}
		return $image;
	}

	public static function createImageFolder() {
		$banner_image_path = Mage::getBaseDir('media') . DS . 'banners';
		if (!is_dir($banner_image_path)) {
			try {
				mkdir($banner_image_path);
				chmod($banner_image_path, 0777);
			} catch (Exception $e) {
			}
		}
	}

	public static function deleteImageFile($image) {
		if (!$image) {
			return;
		}
		$banner_image_path = Mage::getBaseDir('media') . DS . 'banners' . DS . $image;
		if (!file_exists($banner_image_path)) {
			return;
		}
		try {
			unlink($banner_image_path);
		} catch (Exception $e) {
		}
	}

	public function getOptionBlockId() {
		$options = array();
		$model   = Mage::getModel('banner/block')->getCollection();
		foreach ($model as $value)
			$options[] = array(
				'value' => $value->getId(),
				'label' => $value->getName()
			);
		return $options;
	}
}
