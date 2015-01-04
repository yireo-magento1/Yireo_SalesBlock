<?php
/**
 * Yireo SalesBlock for Magento 
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * SalesBlock helper
 */
class Yireo_SalesBlock_Helper_Rule extends Mage_Core_Helper_Abstract
{
    /*
     * Method to check whether the current visitor matches a SalesBlock rule
     *
     * @access public
     * @param null
     * @return bool
     */
    public function hasMatch()
    {
        // Check whether the module is disabled
        if(Mage::helper('salesblock')->enabled() == false) {
            return false;
        }

        // Load all rules and exit if there are no rules
        $rules = Mage::helper('salesblock')->getRules();
        if ($rules->getSize() > 0 == false) {
            return false;
        }

        // Fetch the IP
        $ip = $_SERVER['REMOTE_ADDR'];
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        // Load the customer-record
        $customer = Mage::getModel('customer/session')->getCustomer();
        if($customer->getId() > 0) {
            $customerEmail = $customer->getEmail();
        } else {
            $quote = Mage::getModel('checkout/cart')->getQuote();
            $customerEmail = $quote->getCustomerEmail();
        }

        // Loop through all rules
        foreach($rules as $rule) {
            $ruleIpValues = $rule->getIpValueArray();

            // Direct matches
            if(in_array($ip, $ruleIpValues)) {
                Mage::helper('salesblock/rule')->isMatch($ip, $customerEmail);
                return $rule->getId();

            // Other matches
            } elseif(!empty($ruleIpValues)) {
                foreach($ruleIpValues as $ruleIpValue) {

                    // Convert subnet ranges
                    if(preg_match('/([0-9\.]+)\/([0-9]+)/', $range, $rangeMatch)) {
                        $rip = ip2long($rangeMatch[1]);
                        $ipStart = long2ip((float)$rip);
                        $ipEnd = long2ip((float)($rip | (1<<(32-$rangeMatch[2]))-1));
                        $ruleIpValue = $ipStart.'-'.$ipEnd;
                    }

                    // IP-ranges
                    if(preg_match('/([0-9\.]+)-([0-9\.]+)/', $ruleIpValue, $ipMatch)) {
                        if(version_compare($ip, $ipMatch[1], '>=') && version_compare($ip, $ipMatch[2], '<=')) {
                            Mage::helper('salesblock/rule')->isMatch($ip, $customerEmail);
                            return $rule->getId();
                        }
                    }
                }
            }

            if(!empty($customerEmail)) {
                $ruleEmailValues = $rule->getEmailValueArray();
                foreach($ruleEmailValues as $ruleEmailValue) {
                    if($ruleEmailValue == $customerEmail) {
                        Mage::helper('salesblock/rule')->isMatch($ip, $customerEmail);
                        return $rule->getId();
                    } elseif(stristr($customerEmail, $ruleEmailValue)) {
                        Mage::helper('salesblock/rule')->isMatch($ip, $customerEmail);
                        return $rule->getId();
                    }
                }
            }
        }

        return false;
    }

    /*
     * Method to execute when a visitor is actually matched
     *
     * @access public
     * @param string $ip
     * @param string $email
     * @return null
     */
    public function isMatch($ip, $email)
    {
        $message = 'SalesBlock: IP '.$ip.', email '.$email;
        Mage::log($message ,null, 'salesblock.log');
    }
}
