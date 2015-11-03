<?php
/**
 * Yireo SalesBlock
 *
 * @author Yireo
 * @package SalesBlock
 * @copyright Copyright 2015
 * @license Open Source License (OSL v3)
 * @link http://www.yireo.com
 */
/**
 * SalesBlock observer to various Magento events
 */

class Yireo_SalesBlock_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Method fired on the event <controller_action_predispatch>
     *
     * @param Varien_Event_Observer $observer
     * @return Yireo_SalesBlock_Model_Observer
     */
    public function controllerActionPredispatch($observer)
    {
        // Get the variables
        $module = Mage::app()->getRequest()->getModuleName();
        $controller = Mage::app()->getRequest()->getControllerName();
        $action = Mage::app()->getRequest()->getActionName();

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
            return $this;
        }

        $match = (int)$this->getRuleHelper()->hasMatch();
        if (empty($match)) {
            return $this;
        }

        // Store the match in the session
        Mage::getSingleton('core/session')->setSalesblockRule($match);

        if ($this->getHelper()->isAjax() && in_array($action, array('saveFormValues', 'placeOrder'))) {
            $request = Mage::app()->getRequest();
            $response = Mage::app()->getResponse();
            $action = $request->getActionName();
            $controller = Mage::app()->getFrontController()->getAction();

            $controller->setFlag($action, Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);

            $result = array();
            $result['success'] = false;
            $result['messages'][] = $this->getHelper()->__('Email is incorrect.');
            $jsonData = Mage::helper('core')->jsonEncode($result);
            $response->setBody($jsonData);

            return $this;

        } else {
            /** @var Mage_Sales_Model_Quote $quote */
            $quote = Mage::getModel('checkout/cart')->getQuote();
            $quote->setCustomerEmail('');
            $quote->save();

            $checkoutSession = Mage::getSingleton('checkout/session');
            $data = $checkoutSession->getData('aw_onestepcheckout_form_values');
            $data['billing']['email'] = '';
            $data['shipping']['email'] = '';
            $checkoutSession->setData('aw_onestepcheckout_form_values', $data);

            $url = $this->getHelper()->getUrl();
            if (!empty($url)) {
                Mage::app()->getResponse()->setRedirect($url);
            }
        }

        return $this;
    }

    /**
     * Method fired on the event <sales_quote_save_before>
     *
     * @param $observer
     *
     * @return $this
     * @throws Exception
     */
    public function salesQuoteSaveBefore($observer)
    {
        $match = (int)$this->getRuleHelper()->hasMatch();
        if (empty($match)) {
            return $this;
        }

        $url = $this->getHelper()->getUrl();
        if (!empty($url)) {
            Mage::app()->getResponse()->setRedirect($url);
            return $this;
        }

        throw new Exception('Unable to save quote.');
    }

    /**
     * Method fired on the event <sales_order_place_before>
     *
     * @param $observer
     *
     * @return $this
     * @throws Exception
     */
    public function salesOrderPlaceBefore($observer)
    {
        $match = (int)$this->getRuleHelper()->hasMatch();
        if (empty($match)) {
            return $this;
        }

        $url = $this->getHelper()->getUrl();
        if (!empty($url)) {
            Mage::app()->getResponse()->setRedirect($url);
            return $this;
        }

        throw new Exception('Unable to save order.');
    }

    /**
     * @return Yireo_SalesBlock_Helper_Rule
     */
    protected function getRuleHelper()
    {
        return Mage::helper('salesblock/rule');
    }


    /**
     * @return Yireo_SalesBlock_Helper_Data
     */
    protected function getHelper()
    {
        return Mage::helper('salesblock');
    }
}
