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
CREATE TABLE IF NOT EXISTS {$this->getTable('salesblock_rule')} (
  `rule_id` int(11) NOT NULL auto_increment,
  `label` varchar(255) NOT NULL DEFAULT 0,
  `email_value` TEXT,
  `ip_value` TEXT,
  PRIMARY KEY  (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
