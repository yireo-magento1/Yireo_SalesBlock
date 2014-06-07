<?php
/**
 * Yireo SalesBlock for Magento 
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (C) 2014 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * SalesBlock admin controller
 *
 * @category   SalesBlock
 * @package     Yireo_SalesBlock
 */
class Yireo_SalesBlock_RulesController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Common method
     *
     * @access protected
     * @param null
     * @return Yireo_SalesBlock_RulesController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/salesblock')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('System'), Mage::helper('adminhtml')->__('System'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Sales Block Rules'), Mage::helper('adminhtml')->__('Sales Block Rules'))
        ;
        return $this;
    }

    /**
     * Rules page
     *
     * @access public
     * @param null
     * @return null
     */
    public function indexAction()
    {
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('salesblock/rules'))
            ->renderLayout();
    }

    /**
     * Delete action
     *
     * @access public
     * @param null
     * @return null
     */
    public function deleteAction()
    {
        // Load the rules
        $rule_ids = $this->getRequest()->getParam('rule_id');
        if(!is_array($rule_ids)) $rule_ids = array($rule_ids);

        // Delete the rules
        if(!empty($rule_ids)) {
            foreach($rule_ids as $rule_id) {
                $rule = Mage::getModel('salesblock/rule')->load($rule_id);
                $rule->delete();
            }
        }

        // Set a message
        Mage::getModel('adminhtml/session')->addNotice($this->__('Deleted %s rules succesfully', count($rule_ids)));

        // Redirect
        $this->_redirect('salesblock/rules/index');
    }

    /**
     * Enable action
     *
     * @access public
     * @param null
     * @return null
     */
    public function enableAction()
    {
        // Load the rules
        $rule_ids = $this->getRequest()->getParam('rule_id');
        if(!is_array($rule_ids)) $rule_ids = array($rule_ids);

        // Enable the rules
        if(!empty($rule_ids)) {
            foreach($rule_ids as $rule_id) {
                $rule = Mage::getModel('salesblock/rule')->load($rule_id);
                $rule->setData('status', 1);
                $rule->save();
            }
        }

        // Set a message
        Mage::getModel('adminhtml/session')->addNotice($this->__('Enabled %s rules succesfully', count($rule_ids)));

        // Redirect
        $this->_redirect('salesblock/rules/index');
    }

    /**
     * Disable action
     *
     * @access public
     * @param null
     * @return null
     */
    public function disableAction()
    {
        // Load the rules
        $rule_ids = $this->getRequest()->getParam('rule_id');
        if(!is_array($rule_ids)) $rule_ids = array($rule_ids);

        // Disable the rules
        if(!empty($rule_ids)) {
            foreach($rule_ids as $rule_id) {
                $rule = Mage::getModel('salesblock/rule')->load($rule_id);
                $rule->setData('status', 0);
                $rule->save();
            }
        }

        // Set a message
        Mage::getModel('adminhtml/session')->addNotice($this->__('Disabled %s rules succesfully', count($rule_ids)));

        // Redirect
        $this->_redirect('salesblock/rules/index');
    }
}
