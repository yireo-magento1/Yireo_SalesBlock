<?php

/**
 * SalesBlock plugin for Magento
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */
class Yireo_SalesBlock_Model_Backend_Source_Custompage
{
    protected $_options;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = Mage::getResourceModel('cms/page_collection')
                ->load()->toOptionIdArray();

            array_unshift($this->_options, array('value' => '', 'label' => Mage::helper('salesblock')->__('(No CMS Page, use custom page instead)')));
        }

        return $this->_options;
    }

}
