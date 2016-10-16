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
class Yireo_SalesBlock_Model_Observer_ReplaceRuleVariables
{
    /**
     * @var Yireo_SalesBlock_Helper_Data
     */
    protected $helper;

    /**
     * @var Mage_Core_Model_Session
     */
    protected $coreSession;

    /**
     * @var Yireo_SalesBlock_Model_Rule
     */
    protected $ruleModel;

    /**
     * Yireo_SalesBlock_Model_Observer_ReplaceRuleVariables constructor.
     */
    public function __construct()
    {
        $this->helper = Mage::helper('salesblock');
        $this->coreSession = Mage::getSingleton('core/session');
        $this->ruleModel = Mage::getModel('salesblock/rule');
    }
    
    /**
     * Listen to the event core_block_abstract_to_html_after
     *
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     * @event core_block_abstract_to_html_after
     */
    public function coreBlockAbstractToHtmlAfter($observer)
    {
        if ($this->helper->enabled() == false) {
            return $this;
        }

        $transport = $observer->getEvent()->getTransport();
        $block = $observer->getEvent()->getBlock();
        $variables = $this->getRuleVariablesFromSession();

        if (!is_array($variables)) {
            return $this;
        }

        if ($this->isAllowedBlock($block) == false) {
            return $this;
        }
        
        $html = $transport->getHtml();

        foreach ($variables as $variableName => $variableValue) {
            $html = str_replace('{{var ' . $variableName . '}}', $variableValue, $html);
        }

        $transport->setHtml($html);

        return $this;
    }

    /**
     *
     */
    protected function getRuleVariablesFromSession()
    {
        // Store the match in the session
        $ruleId = $this->coreSession->getSalesblockRule();
        if (empty($ruleId)) {
            return false;
        }

        $rule = $this->ruleModel->load($ruleId);
        if (!$rule->getId() > 0) {
            return false;
        }

        $variables = array(
            'salesblock_rule_frontend_label' => $rule->getFrontendLabel(),
            'salesblock_rule_frontend_text' => $rule->getFrontendText(),
        );

        return $variables;
    }

    /**
     * @param $block
     *
     * @return bool
     */
    protected function isAllowedBlock($block)
    {
        $allowedBlocks = array('content');

        if (in_array($block->getNameInLayout(), $allowedBlocks)) {
            return true;
        }

        return false;
    }
}