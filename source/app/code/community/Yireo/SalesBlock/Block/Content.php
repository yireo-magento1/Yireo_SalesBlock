<?php
/**
 * Yireo SalesBlock for Magento
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 * @link        http://www.yireo.com/
 */

class Yireo_SalesBlock_Block_Content extends Mage_Core_Block_Template
{
    /*
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('salesblock/content.phtml');
        parent::_construct();
    }

    public function getContentTitle()
    {
        $title = $this->getCmsPage()->getContentHeading();
        $ruleTitle = $this->getRule()->getFrontendLabel();
        if(!empty($ruleTitle)) $title = $title.' - '.$ruleTitle;
        return $title;
    }

    public function getContentText()
    {
        $text = $this->getCmsPage()->getContent();
        $ruleText = $this->getRule()->getFrontendText();
        if(!empty($ruleText)) $text = $ruleText;
        return $text;
    }

    public function getCmsPage()
    {
        if(empty($this->cmsPage)) {
            $cmsPageId = Mage::getStoreConfig('salesblock/settings/cmspage');
            $this->cmsPage = Mage::getModel('cms/page')->load($cmsPageId);
        }
        return $this->cmsPage;
    }

    public function getRule()
    {
        $storedRule = (int)Mage::getSingleton('core/session')->getSalesblockRule();
        $rule = Mage::getModel('salesblock/rule')->load($storedRule);
        return $rule;
    }
}
