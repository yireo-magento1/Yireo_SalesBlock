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
