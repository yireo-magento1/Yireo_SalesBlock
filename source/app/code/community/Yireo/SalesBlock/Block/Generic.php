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
class Yireo_SalesBlock_Block_Generic extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * @var Mage_Core_Model_App
     */
    protected $app;

    /**
     * @var Yireo_SalesBlock_Model_Rule
     */
    protected $ruleModel;
    
    /**
     * @var Yireo_SalesBlock_Helper_Data
     */
    protected $helper;

    /**
     * Constructor method
     */
    public function _construct()
    {
        parent::_construct();

        $this->app = Mage::app();
        $this->ruleModel = Mage::getModel('salesblock/rule');
        $this->helper = Mage::helper('salesblock');
    }

    /**
     * Return the version
     *
     * @return string
     */
    public function getVersion()
    {
        $config = Mage::app()->getConfig()->getModuleConfig('Yireo_SalesBlock');
        return (string)$config->version;
    }
}