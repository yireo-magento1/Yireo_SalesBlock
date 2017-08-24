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
class Yireo_SalesBlock_SalesblockrulesController extends Mage_Adminhtml_Controller_Action
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
     * @return Yireo_SalesBlock_SalesblockrulesController
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
     * Rules page
     */
    public function indexAction()
    {
        /** @var Yireo_SalesBlock_Block_Rules $rulesBlock */
        $rulesBlock = $this->getLayout()->createBlock('salesblock/rules');

        $this->_initAction()
            ->_addContent($rulesBlock)
            ->renderLayout();
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        // Load the rules
        $rule_ids = $this->getRequest()->getParam('rule_id');
        if (!is_array($rule_ids)) {
            $rule_ids = array($rule_ids);
        }

        $this->deleteRulesById($rule_ids);

        // Set a message
        $this->getAdminHtmlSession()->addSuccess($this->__('Deleted %s rules succesfully', count($rule_ids)));

        // Redirect
        $this->_redirect('adminhtml/salesblockrules/index');
    }

    /**
     * @param $ruleIds
     */
    protected function deleteRulesById($ruleIds)
    {
        // Delete the rules
        if (!empty($rule_ids)) {
            foreach ($rule_ids as $rule_id) {
                $rule = $this->ruleModel->load($rule_id);
                $rule->delete();
            }
        }
    }

    /**
     * Enable action
     */
    public function enableAction()
    {
        // Load the rules
        $rule_ids = $this->getRequest()->getParam('rule_id');
        if (!is_array($rule_ids)) {
            $rule_ids = array($rule_ids);
        }

        // Enable the rules
        $this->setStatusForRuleIds($rule_ids, 1);

        // Set a message
        $this->getAdminHtmlSession()->addSuccess($this->__('Enabled %s rules succesfully', count($rule_ids)));

        // Redirect
        $this->_redirect('adminhtml/salesblockrules/index');
    }

    /**
     * Disable action
     */
    public function disableAction()
    {
        // Load the rules
        $rule_ids = $this->getRequest()->getParam('rule_id');
        if (!is_array($rule_ids)) {
            $rule_ids = array($rule_ids);
        }

        // Disable the rules
        $this->setStatusForRuleIds($rule_ids, 0);

        // Set a message
        $this->getAdminHtmlSession()->addSuccess($this->__('Disabled %s rules succesfully', count($rule_ids)));

        // Redirect
        $this->_redirect('adminhtml/salesblockrules/index');
    }

    /**
     * @param array $ruleIds
     * @param int $status
     */
    protected function setStatusForRuleIds($ruleIds, $status)
    {
        if (!empty($ruleIds)) {
            foreach ($ruleIds as $rule_id) {
                $this->setRuleStatusById($rule_id, (int) $status);
            }
        }
    }

    /**
     * @param int $ruleId
     * @param int $ruleStatus
     */
    protected function setRuleStatusById($ruleId, $ruleStatus)
    {
        $rule = $this->ruleModel->load($ruleId);
        $rule->setData('status', (int) $ruleStatus);
        $rule->save();
    }

    /**
     * Verify if this action is allowed
     *
     * @return mixed
     */
    protected function _isAllowed()
    {
        $aclResource = 'admin/system/salesblock';

        return $this->getAdminSession()->isAllowed($aclResource);
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
