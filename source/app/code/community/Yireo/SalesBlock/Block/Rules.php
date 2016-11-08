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
class Yireo_SalesBlock_Block_Rules extends Yireo_SalesBlock_Block_Generic
{
    /**
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('salesblock/rules.phtml');

        parent::_construct();
    }

    /**
     * Prepare the layout
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $this->setChild('grid', $this->getLayout()
            ->createBlock('salesblock/rules_grid', 'salesblock.grid')
            ->setSaveParametersInSession(true)
        );

        $newButtonBlock = $this->getButtonBlock('New', 'setLocation(\'' . $this->getNewUrl() . '\')', 'task');
        $this->setChild('new_button', $newButtonBlock);

        return parent::_prepareLayout();
    }


    /**
     * Return a button block with some parameters set
     *
     * @param $label
     * @param $onClick
     * @param $class
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
     * Return the grid HTML block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    /**
     * Return the URL to create a new rule
     *
     * @return string
     */
    public function getNewUrl()
    {
        return $this->getUrl('adminhtml/salesblockrule/edit');
    }

    /**
     * Return the new button HTML
     *
     * @return string
     */
    public function getNewButtonHtml()
    {
        return $this->getChildHtml('new_button');
    }

    /**
     * Helper to return the header of this page
     */
    public function getHeader()
    {
        return $this->__('Sales Block Rules');
    }
}
