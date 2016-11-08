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
class Yireo_SalesBlock_Block_Rule extends Yireo_SalesBlock_Block_Generic
{
    /**
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('salesblock/rule.phtml');
        
        parent::_construct();
    }

    /**
     * Prepare the layout by adding buttons
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $saveButtonBlock = $this->getButtonBlock('Save', 'ruleForm.submit();', 'save');
        $this->setChild('save_button', $saveButtonBlock);

        $applyButtonBlock = $this->getButtonBlock('Apply', 'ruleForm.submit();', 'save');
        $this->setChild('apply_button', $applyButtonBlock);

        $deleteButtonBlock = $this->getButtonBlock('Delete', 'setLocation(\'' . $this->getDeleteUrl() . '\')', 'delete');
        $this->setChild('delete_button', $deleteButtonBlock);

        $backButtonBlock = $this->getButtonBlock('Back', 'setLocation(\'' . $this->getBackUrl() . '\')', 'back');
        $this->setChild('back_button', $backButtonBlock);

        return parent::_prepareLayout();
    }

    /**
     * Return a button block with some parameters set
     *
     * @param string $label
     * @param string $onClick
     * @param string $class
     *
     * @return Mage_Adminhtml_Block_Widget_Button
     */
    protected function getButtonBlock($label, $onClick, $class)
    {
        /** @var Mage_Adminhtml_Block_Widget_Button $buttonBlock */
        $buttonBlock = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label' => Mage::helper('salesblock')->__($label),
                'onclick' => $onClick,
                'class' => $class
            ));

        return $buttonBlock;
    }

    /**
     * Return the URL to save a rule
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('adminhtml/salesblockrule/save');
    }

    /**
     * Return the URL to go back to the overview
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('adminhtml/salesblockrules/index');
    }

    /**
     * Return the URL to delete a rule
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('adminhtml/salesblockrule/delete', array('rule_id' => $this->getRule()->getRuleId()));
    }

    /**
     * Helper to return the header of this page
     */
    public function getHeader()
    {
        return $this->__('Sales Block Rules');
    }

    /**
     * Load a specific rule by URL parameter
     *
     * @return Yireo_SalesBlock_Model_Rule
     * @throws Exception
     */
    public function getRule()
    {
        $ruleId = $this->getRequest()->getParam('rule_id');
        
        if (!empty($ruleId)) {
            $this->ruleModel->load($ruleId);
        }

        return $this->ruleModel;
    }
}
