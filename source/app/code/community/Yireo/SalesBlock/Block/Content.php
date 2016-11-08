<?php

/**
 * Yireo SalesBlock for Magento
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 * @link        https://www.yireo.com/
 */
class Yireo_SalesBlock_Block_Content extends Yireo_SalesBlock_Block_Generic
{
    /**
     * @var Mage_Cms_Model_Page
     */
    protected $cmsPage;

    /**
     * @var Mage_Core_Model_Session
     */
    protected $session;
    
    /**
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('salesblock/content.phtml');
        
        parent::_construct();
        
        $this->cmsPage = Mage::getModel('cms/page');
        $this->session = Mage::getSingleton('core/session');
    }

    /**
     * Get the title of a content page
     *
     * @return string
     */
    public function getContentTitle()
    {
        $title = $this->getCmsPage()->getContentHeading();
        $ruleTitle = $this->getRule()->getFrontendLabel();
        $ruleTitle = trim($ruleTitle);

        if (!empty($ruleTitle)) {
            if (!empty($title)) {
                $title = $title . ' - ' . $ruleTitle;
            } else {
                $title = $ruleTitle;
            }
        }

        if (empty($title)) {
            $title = 'Transaction denied';
        }

        return $title;
    }

    /**
     * Get the body / text of a content page
     *
     * @return mixed
     */
    public function getContentText()
    {
        $text = $this->getCmsPage()->getContent();
        $ruleText = $this->getRule()->getFrontendText();
        if (!empty($ruleText)) {
            $text = $ruleText;
        }

        return $text;
    }

    /**
     * Load a specific CMS page
     *
     * @return Mage_Core_Model_Abstract|mixed
     */
    public function getCmsPage()
    {
        if (empty($this->cmsPage)) {
            $cmsPageId = $this->helper->getStoreConfig('salesblock/settings/cmspage');
            $this->cmsPage = $this->cmsPage->load($cmsPageId);
        }
        return $this->cmsPage;
    }

    /**
     * Load a specific rule
     *
     * @return Yireo_SalesBlock_Model_Rule
     */
    public function getRule()
    {
        $storedRule = (int)$this->session->getSalesblockRule();
        $rule = $this->ruleModel->load($storedRule);
        return $rule;
    }
}
