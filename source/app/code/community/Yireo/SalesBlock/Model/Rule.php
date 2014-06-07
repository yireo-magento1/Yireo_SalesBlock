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
 * SalesBlock model
 */
class Yireo_SalesBlock_Model_Rule extends Mage_Core_Model_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('salesblock/rule');
    }

    public function getIpValueArray()
    {
        $ipValueArray = $this->getData('ip_value_array');
        if(is_array($ipValueArray)) {
            return $ipValueArray;
        }

        $ipValue = $this->getData('ip_value');
        $ipValues = explode("\n", $ipValue);
        $ipValuesArray = array();
        if(!empty($ipValues)) {
            foreach($ipValues as $ipValue) {
                $ipValue = trim($ipValue);
                if(!empty($ipValue)) {
                    $ipValuesArray[] = $ipValue;
                }
            }
        }

        $this->setData('ip_values_array', $ipValuesArray);
        return $ipValuesArray;
    }

    public function getEmailValueArray()
    {
        $emailValueArray = $this->getData('email_value_array');
        if(is_array($emailValueArray)) {
            return $emailValueArray;
        }

        $emailValue = $this->getData('email_value');
        $emailValues = explode("\n", $emailValue);
        $emailValuesArray = array();
        if(!empty($emailValues)) {
            foreach($emailValues as $emailValue) {
                $emailValue = trim($emailValue);
                if(!empty($emailValue)) {
                    $emailValuesArray[] = $emailValue;
                }
            }
        }

        $this->setData('email_values_array', $emailValuesArray);
        return $emailValuesArray;
    }
}
