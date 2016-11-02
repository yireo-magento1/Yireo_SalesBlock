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

    /**
     * Get the IP values of this rule as an array
     *
     * @return array|mixed
     */
    public function getIpValueArray()
    {
        $ipValueArray = $this->getData('ip_value_array');
        if (is_array($ipValueArray)) {
            return $ipValueArray;
        }

        $ipValue = $this->getData('ip_value');
        $ipValuesArray = $this->stringToArray($ipValue);
        $this->setData('ip_values_array', $ipValuesArray);
        return $ipValuesArray;
    }

    /**
     * Get the email values of this rule as an array
     *
     * @return array|mixed
     */
    public function getEmailValueArray()
    {
        $emailValueArray = $this->getData('email_value_array');
        if (is_array($emailValueArray)) {
            return $emailValueArray;
        }

        $emailValue = $this->getData('email_value');
        $emailValuesArray = $this->stringToArray($emailValue);
        $this->setData('email_values_array', $emailValuesArray);
        return $emailValuesArray;
    }

    /**
     * Convert a string into an array
     *
     * @param $string
     *
     * @return array
     */
    public function stringToArray($string)
    {
        $data = preg_split( "/(\n|,|;|\|)/", $string);
        $newData = array();

        foreach ($data as $value) {
            $value = trim($value);
            if (!empty($value)) {
                $newData[] = $value;
            }
        }

        return $newData;
    }
}
