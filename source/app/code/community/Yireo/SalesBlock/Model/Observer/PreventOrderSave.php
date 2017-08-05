<?php
/**
 * Yireo SalesBlock
 *
 * @author Yireo
 * @package SalesBlock
 * @copyright Copyright 2016
 * @license Open Source License (OSL v3)
 * @link https://www.yireo.com
 */

/**
 * SalesBlock observer to various Magento events
 */
class Yireo_SalesBlock_Model_Observer_PreventOrderSave extends Yireo_SalesBlock_Model_Observer
{
    /**
     * Method fired on the event <sales_order_place_before>
     *
     * @param $observer
     *
     * @return $this
     * @throws Yireo_SalesBlock_Exception_SalesDeniedException
     * @event sales_order_place_before
     */
    public function salesOrderPlaceBefore($observer)
    {
        if (!$this->hasMatch()) {
            return $this;
        }

        $url = $this->helper->getUrl();

        if (!empty($url)) {
            $this->redirect($url);
        }

        throw new Yireo_SalesBlock_Exception_SalesDeniedException('Unable to save order.');
    }
}
