<?php
/**
 * Yireo SalesBlock for Magento
 *
 * @package     Yireo_SalesBlock_
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/** @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('salesblock_rule')} MODIFY `frontend_text` TEXT NOT NULL DEFAULT '';
");

$installer->endSetup();
