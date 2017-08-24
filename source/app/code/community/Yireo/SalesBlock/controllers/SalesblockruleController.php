<?php
/**
 * Yireo SalesBlock for Magento
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
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
     * @var Mage_Adminhtml_Model_Session
     */
    protected $adminHtmlSession;

    /**
     * @var Mage_Admin_Model_Session
     */
    protected $adminSession;

    /**
     * @var Yireo_SalesBlock_Model_Rule
     */
    protected $ruleModel;

    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();

        $this->ruleModel = Mage::getModel('salesblock/rule');
    }
    
    /**
     * Common method
     *
     * @return Yireo_SalesBlock_SalesblockruleController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/salesblock')
            ->_addBreadcrumb($this->__('System'), $this->__('System'))
            ->_addBreadcrumb($this->__('Sales Block Rules'), $this->__('Sales Block Rules'));
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
        /** @var Yireo_SalesBlock_Model_Rule $rule */
        $rule = $this->ruleModel;
        if (!empty($rule_id)) {
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
        $this->getAdminHtmlSession()->addSuccess($this->__('Saved rule succesfully'));

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
        if (!empty($rule_id)) {
            $rule = $this->ruleModel->load($rule_id);
            $rule->delete();
        }

        // Set a message
        $this->getAdminHtmlSession()->addSuccess($this->__('Deleted rule succesfully'));

        // Redirect
        $this->_redirect('adminhtml/salesblockrules/index');
    }

    /**
     * Verify if this action is allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        $aclResource = 'admin/system/salesblock';

        return (bool) $this->getAdminSession()->isAllowed($aclResource);
    }

    /**
     * Get session
     *
     * @return mixed
     */
    protected function getAdminHtmlSession()
    {
        if (!$this->adminHtmlSession) {
            $this->adminHtmlSession = Mage::getModel('adminhtml/session');
        }

        return $this->adminHtmlSession;
    }

    /**
     * Get session
     *
     * @return mixed
     */
    protected function getAdminSession()
    {
        if (!$this->adminSession) {
            $this->adminSession = Mage::getModel('admin/session');
        }

        return $this->adminSession;
    }
}
