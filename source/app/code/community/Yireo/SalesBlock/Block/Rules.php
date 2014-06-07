<?php
/**
 * Yireo SalesBlock for Magento
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (C) 2014 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 * @link        http://www.yireo.com/
 */

class Yireo_SalesBlock_Block_Rules extends Mage_Adminhtml_Block_Widget_Container
{
    /*
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('salesblock/rules.phtml');
        parent::_construct();
    }

    protected function _prepareLayout()
    {
        $this->setChild('grid', $this->getLayout()
            ->createBlock('salesblock/rules_grid', 'salesblock.grid')
            ->setSaveParametersInSession(true)
        );

        $this->setChild('new_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('New'),
                    'onclick'   => 'setLocation(\''.$this->getNewUrl().'\')',
                    'class'   => 'task'
                ))
        );

        return parent::_prepareLayout();
    }


    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    public function getNewUrl()
    {
        return $this->getUrl('*/rule/edit');
    }

    public function getNewButtonHtml()
    {
        return $this->getChildHtml('new_button');
    }

    /*
     * Helper to return the header of this page
     */
    public function getHeader()
    {
        return $this->__('Sales Block Rules');
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
