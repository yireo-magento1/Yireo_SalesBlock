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
 * SalesBlock helper
 */
class Yireo_SalesBlock_Helper_Rule extends Mage_Core_Helper_Abstract
{
    /**
     * @var Yireo_SalesBlock_Helper_Data
     */
    protected $helper;

    /**
     * @var Mage_Checkout_Model_Session
     */
    protected $checkoutSession;

    /**
     * @var Mage_Customer_Model_Session
     */
    protected $customerSession;

    /**
     * @var Mage_Checkout_Model_Cart
     */
    protected $cart;

    /**
     * Yireo_SalesBlock_Helper_Rule constructor.
     */
    public function __construct()
    {
        $this->helper = Mage::helper('salesblock');
        $this->checkoutSession = Mage::getSingleton('checkout/session');
        $this->customerSession = Mage::getSingleton('customer/session');
        $this->cart = Mage::getModel('checkout/cart');
    }

    /**
     * Method to check whether the current visitor matches a SalesBlock rule
     *
     * @return false|int
     * @deprecated Use Yireo_SalesBlock_Helper_Rule::getMatchId() instead
     */
    public function hasMatch()
    {
        return $this->getMatchId();
    }
    
    /**
     * Method to check whether the current visitor matches a SalesBlock rule
     *
     * @return false|int
     */
    public function getMatchId()
    {
        // Check whether the module is disabled
        if ($this->helper->enabled() == false) {
            return false;
        }

        // Load all rules and exit if there are no rules
        $rules = $this->helper->getRules();
        if ($rules->getSize() > 0 == false) {
            return false;
        }

        // Fetch the IP
        $ip = $this->getIp();

        // Load the customer-record
        $customerEmail = $this->getCustomerEmail();

        // Loop through all rules
        foreach ($rules as $rule) {

            /** @var Yireo_SalesBlock_Model_Rule $rule */

            $ruleIpValues = $rule->getIpValueArray();

            // Direct IP matches
            if (in_array($ip, $ruleIpValues)) {
                $this->logMatch($rule->getId(), $ip, $customerEmail);
                return $rule->getId();
            }

            // Other matches
            if (!empty($ruleIpValues)) {
                foreach ($ruleIpValues as $ruleIpValue) {
                    if ($this->matchIpRange($ip, $ruleIpValue)) {
                        $this->logMatch($rule->getId(), $ip, $customerEmail);
                        return $rule->getId();
                    }
                }
            }

            // Email matches
            if (!empty($customerEmail)) {
                $ruleEmailValues = $rule->getEmailValueArray();
                foreach ($ruleEmailValues as $ruleEmailValue) {
                    if ($this->hasEmailMatch($customerEmail, $ruleEmailValue)) {
                        $this->logMatch($rule->getId(), $ip, $customerEmail);
                        return $rule->getId();
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param string $ip
     *
     * @return bool
     */
    public function checkSpamIp($ip = null)
    {
        if (empty($ip)) {
            $ip = $this->getIp();
        }

        if (empty($ip)) {
            return false;
        }

        $hostname = implode('.', array_reverse(explode('.', $ip))).'.zen.spamhaus.org';
        $records = dns_get_record($hostname);

        if (empty($records)) {
            return false;
        }

        foreach ($records as $record) {
            if (isset($record['ip']) && in_array($record['ip'], ['127.0.0.2', '127.0.0.3'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Match whether a certain IP matches a certain range string
     *
     * @param $ip
     * @param $rangeString
     *
     * @return bool
     */
    public function matchIpRange($ip, $rangeString)
    {
        // Convert subnet ranges
        if (!preg_match('/([0-9\.]+)\/([0-9]+)/', $rangeString, $rangeMatch)) {
            return false;
        }

        $rip = ip2long($rangeMatch[1]);
        $ipStart = long2ip((float)$rip);
        $ipEnd = long2ip((float)($rip | (1 << (32 - $rangeMatch[2])) - 1));
        $rangeString = $ipStart . '-' . $ipEnd;

        // Check for IP-ranges
        if (!preg_match('/([0-9\.]+)-([0-9\.]+)/', $rangeString, $ipMatch)) {
            return false;
        }

        if (version_compare($ip, $ipMatch[1], '>=') && version_compare($ip, $ipMatch[2], '<=')) {
            return true;
        }

        return false;
    }

    /**
     * Check whether an email matches a pattern
     *
     * @param $email
     * @param $emailPattern
     *
     * @return bool
     */
    public function hasEmailMatch($email, $emailPattern)
    {
        if ($email == $emailPattern) {
            return true;
        }

        if (stristr($email, $emailPattern)) {
            return true;
        }

        return false;
    }

    /**
     * Return the email of the current customer
     *
     * @return mixed
     */
    protected function getCustomerEmail()
    {
        // Load the customer-record
        $customer = $this->customerSession->getCustomer();
        if ($customer->getId() > 0) {
            $customerEmail = $customer->getEmail();
            if (!empty($customerEmail)) {
                return $customerEmail;
            }
        }

        // Check the quote
        $quote = $this->cart->getQuote();
        $customerEmail = $quote->getCustomerEmail();
        if (!empty($customerEmail)) {
            return $customerEmail;
        }

        // Check for AW Onestepcheckout form values
        $data = $this->checkoutSession->getData('aw_onestepcheckout_form_values');
        if (is_array($data) && !empty($data['billing']['email'])) {
            $customerEmail = $data['billing']['email'];
        }

        return $customerEmail;
    }

    /**
     * Get the current IP address
     *
     * @return string
     */
    public function getIp()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $ip;
    }

    /**
     * Method to execute when a visitor is actually matched
     *
     * @param int $ruleId
     * @param string $ip
     * @param string $email
     *
     */
    public function logMatch($ruleId, $ip, $email)
    {
        $message = 'SalesBlock: rule '. $ruleId .', IP ' . $ip . ', email ' . $email;
        Mage::log($message, null, 'salesblock.log');
    }
}
