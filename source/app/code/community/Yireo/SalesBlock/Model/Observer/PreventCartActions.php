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
class Yireo_SalesBlock_Model_Observer_PreventCartActions extends Yireo_SalesBlock_Model_Observer
{
    /**
     * @var Mage_Checkout_Model_Cart
     */
    protected $cart;

    /**
     * @var Mage_Checkout_Model_Session
     */
    protected $checkoutSession;

    /**
     * Yireo_SalesBlock_Model_Observer_PreventCartActions constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->cart = Mage::getModel('checkout/cart');
        $this->checkoutSession = Mage::getSingleton('checkout/session');
    }

    /**
     * Method fired on the event <controller_action_predispatch>
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Yireo_SalesBlock_Model_Observer
     */
    public function controllerActionPredispatch($observer)
    {
        if ($this->allowPrevention() == false) {
            return $this;
        }

        $match = (int)$this->ruleHelper->getMatchId();
        if (empty($match)) {
            return $this;
        }

        $this->storeData($match);

        if ($this->modifyAjaxCall() == true) {
            return $this;
        }

        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $this->cart->getQuote();
        $quote->setCustomerEmail('');
        $quote->save();

        $checkoutSession = $this->checkoutSession;
        $data = $checkoutSession->getData('aw_onestepcheckout_form_values');
        $data['billing']['email'] = '';
        $data['shipping']['email'] = '';
        $checkoutSession->setData('aw_onestepcheckout_form_values', $data);

        $url = $this->helper->getUrl();
        if (!empty($url)) {
            $this->response->setRedirect($url);
        }

        return $this;
    }

    /**
     * @return bool
     */
    protected function modifyAjaxCall()
    {
        if ($this->helper->isAjax() == false) {
            return false;
        }

        $action = $this->request->getActionName();
        if (!in_array($action, array('saveFormValues', 'placeOrder'))) {
            return false;
        }

        $request = $this->request;
        $response = $this->response;
        $action = $request->getActionName();

        $frontControllerAction = Mage::app()->getFrontController()->getAction();
        $frontControllerAction->setFlag($action, Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);

        $result = array();
        $result['success'] = false;
        $result['messages'][] = $this->helper->__('Email is incorrect.');
        $jsonData = Mage::helper('core')->jsonEncode($result);
        $response->setBody($jsonData);

        return true;
    }

    /**
     * @return bool
     */
    protected function allowPrevention()
    {
        // Get the variables
        $module = $this->request->getModuleName();
        $controller = $this->request->getControllerName();
        $action = $this->request->getActionName();

        $includeControllers = array('onepage', 'multishipping');
        $includeModules = array('onestep', 'onestepcheckout');
        $excludeActions = array('saveAddress');

        $match = false;
        if (in_array($controller, $includeControllers)) {
            $match = true;
        } elseif (in_array($module, $includeModules)) {
            $match = true;
        }

        if (in_array($action, $excludeActions)) {
            $match = false;
        }

        if ($match == false) {
            return false;
        }

        return true;
    }
}