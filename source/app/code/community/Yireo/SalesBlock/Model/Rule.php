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
     * @return int
     */
    public function getRuleId()
    {
        return $this->getData('rule_id');
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getData('label');
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->setData('label', $label);
    }

    /**
     * @return string
     */
    public function getFrontendLabel()
    {
        return $this->getData('frontend_label');
    }

    /**
     * @param string $frontendLabel
     */
    public function setFrontendLabel($frontendLabel)
    {
        $this->setData('frontend_label', $frontendLabel);
    }

    /**
     * @return string
     */
    public function getFrontendText()
    {
        return $this->getData('frontend_text');
    }

    /**
     * @param string $frontendText
     */
    public function setFrontendText($frontendText)
    {
        $this->setData('frontend_text', $frontendText);
    }

    /**
     * @return string
     */
    public function getEmailValue()
    {
        return $this->getData('email_value');
    }

    /**
     * @param string $emailValue
     */
    public function setEmailValue($emailValue)
    {
        $this->setData('email_value', $emailValue);
    }

    /**
     * @return string
     */
    public function getIpValue()
    {
        return $this->getData('ip_value');
    }

    /**
     * @param string $emailValue
     */
    public function setIpValue($emailValue)
    {
        $this->setData('ip_value', $emailValue);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getData('status');
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->setData('status', $status);
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
