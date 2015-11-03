<?php
/**
 * Yireo SalesBlock for Magento 
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * SalesBlock admin controller
 *
 * @category   SalesBlock
 * @package     Yireo_SalesBlock
 */
class Yireo_SalesBlock_SalesblockruleController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Common method
     *
     * @return Yireo_SalesBlock_RuleController
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
     * Edit action 
     */
    public function editAction()
    {
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('salesblock/rule'))
            ->renderLayout();
    }

    /**
     * Save action
     */
    public function saveAction()
    {
        // Load the rules
        $rule_id = $this->getRequest()->getParam('rule_id');

        // Initialize the rule
        $rule = Mage::getModel('salesblock/rule');
        if(!empty($rule_id)) {
            $rule->load($rule_id);
        }

        // Set the POST-data
        $rule->setLabel($this->getRequest()->getParam('label'));
        $rule->setEmailValue($this->getRequest()->getParam('email_value'));
        $rule->setIpValue($this->getRequest()->getParam('ip_value'));
        $rule->setFrontendLabel($this->getRequest()->getParam('frontend_label'));
        $rule->setFrontendText($this->getRequest()->getParam('frontend_text'));
        $rule->setStatus($this->getRequest()->getParam('status'));
        $rule->save();

        // Set a message
        Mage::getModel('adminhtml/session')->addNotice($this->__('Saved rule succesfully'));

        // Redirect
        $this->_redirect('adminhtml/salesblockrules/index');
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        // Load the rules
        $rule_id = $this->getRequest()->getParam('rule_id');

        // Delete the rule
        if(!empty($rule_id)) {
            $rule = Mage::getModel('salesblock/rule')->load($rule_id);
            $rule->delete();
        }

        // Set a message
        Mage::getModel('adminhtml/session')->addNotice($this->__('Deleted rule succesfully'));

        // Redirect
        $this->_redirect('adminhtml/salesblockrules/index');
    }

    /**
     * Verify if this action is allowed
     *
     * @return mixed
     */
    protected function _isAllowed()
    {
        $aclResource = 'admin/system/salesblock';

        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}
