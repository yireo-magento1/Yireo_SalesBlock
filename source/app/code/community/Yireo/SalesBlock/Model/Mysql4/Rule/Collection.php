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
 * SalesBlock item resource collection
 */
class Yireo_SalesBlock_Model_Mysql4_Rule_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('salesblock/rule');
    }
}
