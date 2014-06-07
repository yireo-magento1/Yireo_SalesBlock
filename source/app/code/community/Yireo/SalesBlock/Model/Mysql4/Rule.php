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
 * SalesBlock resource model
 */
class Yireo_SalesBlock_Model_Mysql4_Rule extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('salesblock/rule', 'rule_id');
    }
}
