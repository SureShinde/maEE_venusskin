<?php
/**
 * Banana ContentManager Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@advancedcontentmanager.com so we can send you a copy immediately.
 *
 * @category	Banana
 * @package		Banana_ContentManager
 * @copyright	Copyright (c) 2014 Banana Content Manager (http://www.advancedcontentmanager.com)
 * @author		Banana Team
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version		1.2.4
 */

class Banana_ContentManager_Model_Observer {
    
    public function cleanCache($observer) {
        
        //delete image cache folders
	$helper = Mage::helper('contentmanager');
	
        $folder = Mage::getBaseDir('media') . DS . $helper->getCtImageCroppedFolder();
        foreach($this->_findAllFiles($folder) as $dir)
        {
            $this->_emptyFolder($dir, true);
        }
    }
    
    private function _findAllFiles($dir) 
    { 
        $root = scandir($dir); 
        foreach($root as $value) 
        { 
            if($value === '.' || $value === '..') {continue;} 
            if(is_dir("$dir/$value") && $value == 'cache') {$result[]="$dir/$value";continue;}
            if(is_file("$dir/$value")) { continue; }
            foreach($this->_findAllFiles("$dir/$value") as $value) 
            { 
                $result[]=$value; 
            } 
        } 
        return $result; 
    } 
    
    private function _emptyFolder($folder, $first = false)
    {
        $open=@opendir($folder);
        if (!$open) return;
        while($file=readdir($open)) {
                if ($file == '.' || $file == '..') continue;
                        if (is_dir($folder. DS .$file)) {
                                $r=$this->_emptyFolder($folder. DS .$file);
                                if (!$r) return false;
                        }
                        else {
                                $r=@unlink($folder. DS .$file);
                                if (!$r) return false;
                        }
        }
        closedir($open);
        
        if(!$first) //delete the main folder
            $r=@rmdir($folder);
        
        if (!$r) return false;
            return true;
    }
    
}