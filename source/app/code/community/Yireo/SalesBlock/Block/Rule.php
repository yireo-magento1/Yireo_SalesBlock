<?php
/**
 * Yireo SalesBlock for Magento
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 * @link        http://www.yireo.com/
 */

class Yireo_SalesBlock_Block_Rule extends Mage_Adminhtml_Block_Widget_Container
{
    /*
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('salesblock/rule.phtml');
        parent::_construct();
    }

    protected function _prepareLayout()
    {
        $this->setChild('save_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('salesblock')->__('Save'),
                    'onclick' => 'ruleForm.submit();',
                    'class' => 'save'
                ))
        );

        $this->setChild('apply_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('salesblock')->__('Apply'),
                    'onclick' => 'ruleForm.submit();',
                    'class' => 'save'
                ))
        );

        if($this->getRule()->getRuleId() > 0) {
            $this->setChild('delete_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label' => Mage::helper('salesblock')->__('Delete'),
                        'onclick' => 'setLocation(\''.$this->getBackUrl().'\')',
                        'class' => 'delete'
                    ))
            );
        }

        $this->setChild('back_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('salesblock')->__('Back'),
                    'onclick' => 'setLocation(\''.$this->getBackUrl().'\')',
                    'class' => 'back'
                ))
        );


        return parent::_prepareLayout();
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/rule/save');
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/rules/index');
    }

    public function getDeleteUrl()
    {
        return $this->getUrl('*/rule/delete', array('rule_id' => $this->getRule()->getRuleId()));
    }

    /*
     * Helper to return the header of this page
     */
    public function getHeader()
    {
        return $this->__('Sales Block Rules');
    }

    public function getRule()
    {
        $rule_id = $this->getRequest()->getParam('rule_id');
        $rule = Mage::getModel('salesblock/rule');
        if(!empty($rule_id)) {
            $rule->load($rule_id);
        }

        return $rule;
    }

    /**
     * Return the version
     *
     * @access public
     * @param null
     * @return string
     */
    public function getVersion()
    {
        $config = Mage::app()->getConfig()->getModuleConfig('Yireo_SalesBlock');
        return (string)$config->version;
    }
}
