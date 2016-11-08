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
    /**
     * @var array
     */
    protected $_options;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_options) {

            /** @var Mage_Cms_Model_Resource_Page_Collection $cmsPages */
            $cmsPages = Mage::getResourceModel('cms/page_collection');

            $this->_options = $cmsPages
                ->load()
                ->toOptionIdArray();

            array_unshift($this->_options, array('value' => '', 'label' => $this->getNoCmsPageLabel()));
        }

        return $this->_options;
    }

    /**
     * @return string
     */
    protected function getNoCmsPageLabel()
    {
        return Mage::helper('salesblock')->__('(No CMS Page, use custom page instead)');
    }
}
