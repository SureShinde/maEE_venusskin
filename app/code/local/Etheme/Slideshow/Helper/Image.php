<?php
class Etheme_Slideshow_Helper_Image extends Mage_Core_Helper_Abstract {
    
    
    /**
     * Uploads an image using magento uploader
     * @param $filename - name of file in $_FILES
     * @param $slideId - id of current slide will be included to image name
     * @param $sliderId - id of slider will be included to folder name
     */         
    public function uploadImage($filename, $sliderId, $slideId) {
        
        if ($sliderId && $slideId) {
            $uploader = new Varien_File_Uploader($filename);
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            $ext = pathinfo($_FILES[$filename]['name'], PATHINFO_EXTENSION);
            $dir = $this->getImgDir($sliderId);
            $path = Mage::getBaseDir('media') . DS . $dir;
            $name = $this->getImgName($slideId, $filename, $ext);
            $uploader->save($path , $name);
            return $dir . $name;
        }
        else 
            Mage::throwException(Mage::helper('etheme_slideshow')->__('Image uploading error: invalid arguments'));
    }

    
    /**
     * duplicate image assigned to sourceSlide into other slide
     * @param $srcPath - path of image to copy from
     * @param $imgName - name of image (background, layer, etc.)
     * @param $srcId - id of source slide
     * @param $dstId - id of destination slide
     * @param $dstSliderId - id of destination slider
     * @return string $dstPath - path to the new image or empty string if source doesn't have image
     */
    public function copyImage($srcPath, $imgName, $srcId, $dstId, $dstSliderId) {
        
        Mage::log($srcPath, true, 'copy_image.log');
        if ($srcPath && $srcPath != '') {
            $srcPathExp = explode('.', $srcPath);
            $ext = end($srcPathExp); 
            $srcPath = Mage::getBaseDir('media') . DS . $srcPath;
            $dstPath = $this->getImgDir($dstSliderId) . $this->getImgName($dstId, $imgName, $ext);
            $dstPathFull = Mage::getBaseDir('media') . DS . $dstPath;
            if (!file_exists(dirname($dstPathFull))) {
                if (!mkdir(dirname($dstPathFull), 0777, true))
                    Mage::throwException(Mage::helper('etheme_slideshow')->__('Unable to create slider directory'));
            }
                        
            if (!copy($srcPath, $dstPathFull))
                Mage::throwException(Mage::helper('etheme_slideshow')->__('Unable to copy file from source slide'));
            return $dstPath;
        }
        else
            return '';
    }
    
    private function getImgDir($sliderId) {
        
        return 'etheme' . DS . 'slideshow' . DS . 'slider_' . $sliderId . DS;
    }
    
    private function getImgName($slideId, $imgName, $imgExt) {
        
        return 'slide_' . $slideId . '_' . $imgName . '.' . $imgExt;
    }
    
}