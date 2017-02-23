<?php
class Snowdog_SnowOpenGraph_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_SNOWGRAPH_IMAGE = 'snowopengraph/general/image';
    const XML_PATH_SNOWGRAPH_PROMO_IMAGE = 'snowopengraph/general/promoimage';

    public function getOpenGraphImage()
    {
        $imgName = Mage::getStoreConfig(self::XML_PATH_SNOWGRAPH_IMAGE);
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'opengraph' . DS . $imgName;
    }

    public function getOpenGraphPromoImage()
    {
        $imgName = Mage::getStoreConfig(self::XML_PATH_SNOWGRAPH_PROMO_IMAGE);
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'opengraph' . DS . $imgName;
    }
}
	 