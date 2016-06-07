<?php
/**
 * Yireo SalesBlock for Magento
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * SalesBlock helper
 */
class Yireo_SalesBlock_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var Mage_Core_Model_App
     */
    protected $app;

    /**
     * @var Mage_Cms_Helper_Page
     */
    protected $cmsHelper;

    /**
     * @var Yireo_SalesBlock_Model_Rule
     */
    protected $ruleModel;

    /**
     * Yireo_SalesBlock_Helper_Data constructor.
     */
    public function __construct()
    {
        $this->app = Mage::app();
        $this->cmsHelper = Mage::helper('cms/page');
        $this->ruleModel = Mage::getModel('salesblock/rule');
    }
    
    /**
     * Helper-method to check if this module is enabled
     *
     * @return bool
     */
    public function enabled()
    {
        if ((bool)$this->getStoreConfig('advanced/modules_disable_output/Yireo_SalesBlock')) {
            return false;
        }

        return (bool)$this->getStoreConfig('salesblock/settings/enabled');
    }

    /**
     * Helper-method to fetch all rules
     *
     * @return Mage_Core_Model_Resource_Db_Collection_Abstract
     */
    public function getRules()
    {
        $rules = $this->ruleModel->getCollection()->addFieldToFilter('status', 1);
        return $rules;
    }

    /**
     * Determine the right URL for the custom deny page
     *
     * @return string
     */
    public function getUrl()
    {
        $custom_page = (int)$this->getStoreConfig('salesblock/settings/custom_page');
        $cmsPageId = $this->getStoreConfig('salesblock/settings/cmspage');
        $cmsPageUrl = $this->cmsHelper->getPageUrl($cmsPageId);

        if ($custom_page == 1 || empty($cmsPageUrl)) {
            return Mage::getUrl('salesblock');
        } else {
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
        $request = $this->app->getRequest();

        if ($request->isXmlHttpRequest()) {
            return true;
        }

        if ($request->getParam('ajax') || $request->getParam('isAjax')) {
            return true;
        }

        return false;
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    public function getStoreConfig($value)
    {
        return $this->app->getStore()->getConfig($value);
    }
}
