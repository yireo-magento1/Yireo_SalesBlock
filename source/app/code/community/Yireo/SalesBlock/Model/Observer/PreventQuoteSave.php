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
class Yireo_SalesBlock_Model_Observer_PreventQuoteSave extends Yireo_SalesBlock_Model_Observer
{
    /**
     * Method fired on the event <sales_quote_save_before>
     *
     * @param $observer
     *
     * @return $this
     * @throws Yireo_SalesBlock_Lib_Exception_SalesDeniedException
     * @event sales_quote_place_before
     */
    public function salesQuoteSaveBefore($observer)
    {
        $match = (int)$this->ruleHelper->hasMatch();
        if (empty($match)) {
            return $this;
        }

        $this->storeData($match);

        $url = $this->helper->getUrl();
        if (!empty($url)) {
            $this->response->setRedirect($url);
            return $this;
        }

        throw new Yireo_SalesBlock_Lib_Exception_SalesDeniedException('Unable to save quote.');
    }
}
