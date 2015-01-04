<?php
/**
 * Yireo SalesBlock
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * SalesBlock default controller
 */
class Yireo_SalesBlock_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Display denied page
     */
    public function indexAction()
    {
        // Load the layout
        $this->loadLayout();
        $this->renderLayout();
    }
}
