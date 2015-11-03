<?php
/**
 * Yireo SalesBlock for Magento 
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * SalesBlock helper
 */
class Yireo_SalesBlock_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Helper-method to check if this module is enabled
     *
     * @return bool
     */
    public function enabled()
    {
        if ((bool)Mage::getStoreConfig('advanced/modules_disable_output/Yireo_SalesBlock')) {
            return false;
        }

        return (bool)Mage::getStoreConfig('salesblock/settings/enabled');
    }

    /**
     * Helper-method to fetch all rules
     *
     * @return bool
     */
    public function getRules()
    {
        $rules = Mage::getModel('salesblock/rule')->getCollection()->addFieldToFilter('status', 1);
        return $rules;
    }

    /**
     * Determine the right URL for the custom deny page
     *
     * @return string
     */
    public function getUrl()
    {
        $custom_page = (int)Mage::getStoreConfig('salesblock/settings/custom_page');
        if($custom_page == 1) {
            return Mage::getUrl('salesblock');
        } else {
            $cmsPageId = Mage::getStoreConfig('salesblock/settings/cmspage');
            $cmsPageUrl = Mage::helper('cms/page')->getPageUrl($cmsPageId);
            return $cmsPageUrl;
        }
    }

    /**
     * Determine whether the current request is AJAX
     *
     * @return bool
     */
    public function isAjax()
    {
        $request = Mage::app()->getRequest();

        if ($request->isXmlHttpRequest()) {
            return true;
        }

        if ($request->getParam('ajax') || $request->getParam('isAjax')) {
            return true;
        }

        return false;
    }
}
