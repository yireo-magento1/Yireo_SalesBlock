<?php
/**
 * Yireo SalesBlock
 *
 * @author Yireo
 * @package SalesBlock
 * @copyright Copyright 2016
 * @license Open Source License (OSL v3)
 * @link https://www.yireo.com
 */

/**
 * SalesBlock observer to various Magento events
 */
class Yireo_SalesBlock_Model_Observer
{
    /**
     * @var Yireo_SalesBlock_Helper_Rule
     */
    protected $ruleHelper;

    /**
     * @var Yireo_SalesBlock_Helper_Data
     */
    protected $helper;

    /**
     * @var Mage_Core_Controller_Request_Http
     */
    protected $request;

    /**
     * @var Zend_Controller_Response_Http
     */
    protected $response;

    /**
     * @var Mage_Core_Model_Session
     */
    protected $session;
    
    /**
     * Yireo_SalesBlock_Model_Observer constructor.
     */
    public function __construct()
    {
        $this->ruleHelper = Mage::helper('salesblock/rule');
        $this->helper = Mage::helper('salesblock');
        $this->request = Mage::app()->getRequest();
        $this->response = Mage::app()->getResponse();
        $this->session = Mage::getSingleton('core/session');
    }

    /**
     * Method fired on the event <controller_action_predispatch>
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Yireo_SalesBlock_Model_Observer
     * @deprecated See Yireo_SalesBlock_Model_Observer_PreventCartActions
     */
    public function controllerActionPredispatch($observer)
    {
        return $this;
    }

    /**
     * Method fired on the event <sales_quote_save_before>
     *
     * @param $observer
     *
     * @return $this
     * @deprecated See Yireo_SalesBlock_Model_Observer_PreventQuoteSave
     */
    public function salesQuoteSaveBefore($observer)
    {
        return $this;
    }

    /**
     * Method fired on the event <sales_order_place_before>
     *
     * @param $observer
     *
     * @return $this
     * @deprecated Yireo_SalesBlock_Model_Observer_PreventOrderSave
     */
    public function salesOrderPlaceBefore($observer)
    {
        return $this;
    }

    /**
     * Listen to the event core_block_abstract_to_html_after
     *
     * @parameter Varien_Event_Observer $observer
     *
     * @return $this
     * @deprecated See Yireo_SalesBlock_Model_Observer_ReplaceRuleVariables
     */
    public function coreBlockAbstractToHtmlAfter($observer)
    {
        return $this;
    }

    /**
     * Helper method to store data in the session
     */
    protected function storeData($match)
    {
        // Store the match in the session
        $this->session->setData('salesblock_rule', $match);
    }
}
