<?php
/**
 * Yireo SalesBlock for Magento
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 * @link        https://www.yireo.com/
 */

/**
 * Class Yireo_SalesBlock_Proxy_Core_Session
 */
class Yireo_SalesBlock_Proxy_Core_Session
{
    /**
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func([$this->_getProxyTarget(), $method], $arguments);
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function _getProxyTarget()
    {
        return Mage::getSingleton('core/session');
    }
}