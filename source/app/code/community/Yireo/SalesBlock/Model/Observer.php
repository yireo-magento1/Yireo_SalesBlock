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

        $this->storeData($match);

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
     * @throws Yireo_SalesBlock_Lib_Exception_SalesDeniedException
     */
    public function salesQuoteSaveBefore($observer)
    {
        $match = (int)$this->getRuleHelper()->hasMatch();
        if (empty($match)) {
            return $this;
        }

        $this->storeData($match);

        $url = $this->getHelper()->getUrl();
        if (!empty($url)) {
            Mage::app()->getResponse()->setRedirect($url);
            return $this;
        }

        throw new Yireo_SalesBlock_Lib_Exception_SalesDeniedException('Unable to save quote.');
    }

    /**
     * Method fired on the event <sales_order_place_before>
     *
     * @param $observer
     *
     * @return $this
     * @throws Yireo_SalesBlock_Lib_Exception_SalesDeniedException
     */
    public function salesOrderPlaceBefore($observer)
    {
        $match = (int)$this->getRuleHelper()->hasMatch();
        if (empty($match)) {
            return $this;
        }

        $this->storeData($match);

        $url = $this->getHelper()->getUrl();
        if (!empty($url)) {
            Mage::app()->getResponse()->setRedirect($url);
            return $this;
        }

        throw new Yireo_SalesBlock_Lib_Exception_SalesDeniedException('Unable to save order.');
    }

    /**
     * Listen to the event core_block_abstract_to_html_after
     *
     * @parameter Varien_Event_Observer $observer
     * @return $this
     */
    public function coreBlockAbstractToHtmlAfter($observer)
    {
        if($this->getHelper()->enabled() == false) {
            return $this;
        }

        $transport = $observer->getEvent()->getTransport();
        $block = $observer->getEvent()->getBlock();
        $variables = $this->getRuleVariablesFromSession();

        if (!is_array($variables)) {
            return $this;
        }

        if($this->isAllowedBlock($block) == false) {
            return $this;
        }
            
        $html = $transport->getHtml();

        foreach ($variables as $variableName => $variableValue) {
            $html = str_replace('{{var '.$variableName.'}}', $variableValue, $html);
        }

        $transport->setHtml($html);

        return $this;
    }

    /**
     *
     */
    protected function storeData($match)
    {
        // Store the match in the session
        Mage::getSingleton('core/session')->setSalesblockRule($match);
    }

    /**
     *
     */
    protected function getRuleVariablesFromSession()
    {
        // Store the match in the session
        $ruleId = Mage::getSingleton('core/session')->getSalesblockRule();
        if (empty($ruleId)) {
            return false;
        }
        
        $rule = Mage::getModel('salesblock/rule')->load($ruleId);
        if (!$rule->getId() > 0) {
            return false;
        }

        $variables = array(
            'salesblock_rule_frontend_label' => $rule->getFrontendLabel(),
            'salesblock_rule_frontend_text' => $rule->getFrontendText(),
        );

        return $variables;
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

    protected function isAllowedBlock($block)
    {
        $allowedBlocks = array('content');

        if(in_array($block->getNameInLayout(), $allowedBlocks)) {
            return true;
        }

        return false;
    }
}
