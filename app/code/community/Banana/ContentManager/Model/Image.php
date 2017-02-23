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

class Banana_ContentManager_Model_Image extends Varien_Image
{
    /**
     * Retrieve image adapter object
     *
     * @param string $adapter
     * @return Varien_Image_Adapter_Abstract
     */
    protected function _getAdapter($adapter=null)
    {
        if( !isset($this->_adapter) ) {
            $this->_adapter = new Banana_ContentManager_Model_Image_Adapter();
        }
        return $this->_adapter;
    }
    
    /**
     * Crop an image.
     *
     * @param int $top. Default value is 0
     * @param int $left. Default value is 0
     * @param int $right. Default value is 0
     * @param int $bottom. Default value is 0
     * @access public
     * @return void
     */
    public function crop($top=0, $left=0, $right=0, $bottom=0)
    {
        $this->_getAdapter()->crop($top, $left, $right, $bottom);
    }
}
