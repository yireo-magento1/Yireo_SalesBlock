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

/*
 * SalesBlock observer to various Magento events
 */
class Yireo_SalesBlock_Model_Observer extends Mage_Core_Model_Abstract
{
    /*
     * Method fired on the event <controller_action_predispatch>
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Yireo_SalesBlock_Model_Observer
     */
    public function controllerActionPredispatch($observer)
    {
        // Get the variables
        $module = Mage::app()->getRequest()->getModuleName();
        $controller = Mage::app()->getRequest()->getControllerName();
        $action = Mage::app()->getRequest()->getActionName();

        $match = false;
        if (in_array($controller, array('onepage', 'multishipping'))) {
            $match = true;
        } elseif (in_array($module, array('onestep', 'onestepcheckout'))) {
            $match = true;
        }

        if ($match == false) {
            return $this;
        }

        $match = (int)Mage::helper('salesblock/rule')->hasMatch();
        if (empty($match)) {
            return $this;
        }

        // Store the match in the session
        Mage::getSingleton('core/session')->setSalesblockRule($match);

        $url = Mage::helper('salesblock')->getUrl();
        if(!empty($url)) {
            Mage::app()->getResponse()->setRedirect($url);
        }
        return $this;
    }
}
