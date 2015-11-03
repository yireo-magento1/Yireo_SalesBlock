<?php
/**
 * Yireo SalesBlock for Magento
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 * @link        http://www.yireo.com/
 */

/**
 * Class Yireo_SalesBlock_Block_Rules_Grid
 */
class Yireo_SalesBlock_Block_Rules_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Add grid things to the constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('salesblockGrid');
        $this->setDefaultSort('rule_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);

    }

    /**
     * Prepare the salesblock collection loaded from the database
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('salesblock/rule')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();

        $collection = $this->getCollection();
        foreach($collection as $itemKey => $item) {
            $item->setEmailValue(nl2br($item->getEmailValue()));
            $item->setIpValue(nl2br($item->getIpValue()));
        }

        $this->setCollection($collection);
        return $this;
    }

    /**
     * Prepare the columns in this grid
     *
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('rule_id', array(
            'header'=> Mage::helper('salesblock')->__('Rule ID'),
            'width' => '50px',
            'index' => 'rule_id',
            'type' => 'number',
			'sortable' => false,
			'filter' => false,
        ));

        $this->addColumn('label', array(
            'header'=> Mage::helper('salesblock')->__('Label'),
            'index' => 'label',
            'type' => 'text',
        ));

        $this->addColumn('email_value', array(
            'header'=> Mage::helper('salesblock')->__('Email Value'),
            'index' => 'email_value',
            'type' => 'text',
        ));

        $this->addColumn('ip_value', array(
            'header'=> Mage::helper('salesblock')->__('IP Value'),
            'index' => 'ip_value',
            'type' => 'text',
        ));

        $this->addColumn('status', array(
            'header'=> Mage::helper('salesblock')->__('Status'),
            'index' => 'status',
            'type' => 'text',
        ));

        $this->addColumn('actions', array(
            'header'=> Mage::helper('salesblock')->__('Action'),
            'type' => 'action',
            'getter' => 'getRuleId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('salesblock')->__('Edit'),
                    'url' => array('base' => 'adminhtml/salesblockrule/edit'),
                    'field' => 'rule_id'
                ),
                array(
                    'caption' => Mage::helper('salesblock')->__('Delete'),
                    'url' => array('base' => 'adminhtml/salesblockrules/delete'),
                    'field' => 'rule_id'
                ),
                array(
                    'caption' => Mage::helper('salesblock')->__('Disable'),
                    'url' => array('base' => 'adminhtml/salesblockrules/disable'),
                    'field' => 'rule_id'
                ),
                array(
                    'caption' => Mage::helper('salesblock')->__('Enable'),
                    'url' => array('base' => 'adminhtml/salesblockrules/enable'),
                    'field' => 'rule_id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
        ));

        return parent::_prepareColumns();
    }

    /**
     * Prepare mass actions
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('rule_id');
        $this->getMassactionBlock()->setFormFieldName('rule_id');
        $this->getMassactionBlock()->setUseSelectAll(false);

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('salesblock')->__('Delete'),
            'url'  => $this->getUrl('adminhtml/salesblockrules/delete'),
        ));

        $this->getMassactionBlock()->addItem('disable', array(
            'label'=> Mage::helper('salesblock')->__('Disable'),
            'url'  => $this->getUrl('adminhtml/salesblockrules/disable'),
        ));

        $this->getMassactionBlock()->addItem('enable', array(
            'label'=> Mage::helper('salesblock')->__('Enable'),
            'url'  => $this->getUrl('adminhtml/salesblockrules/enable'),
        ));
    }

    /**
     * Get the edit URL for a specific sales block rule
     *
     * @param $row
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/salesblockrule/edit', array('rule_id' => $row->getRuleId()));
    }

}
