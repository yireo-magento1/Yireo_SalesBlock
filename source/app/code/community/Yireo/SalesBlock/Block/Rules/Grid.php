<?php
/**
 * Yireo SalesBlock for Magento
 *
 * @package     Yireo_SalesBlock
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 * @link        https://www.yireo.com/
 */

/**
 * Class Yireo_SalesBlock_Block_Rules_Grid
 */
class Yireo_SalesBlock_Block_Rules_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * @var Yireo_SalesBlock_Helper_Data
     */
    protected $helper;

    /**
     * Add grid things to the constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->helper = Mage::helper('salesblock');

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
        foreach ($collection as $itemKey => $item) {
            $this->_prepareItem($item);
        }

        $this->setCollection($collection);
        return $this;
    }

    /**
     * @param $item
     */
    protected function _prepareItem(&$item)
    {
        $item->setEmailValue(nl2br($item->getEmailValue()));
        $item->setIpValue(nl2br($item->getIpValue()));
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
            'header' => $this->helper->__('Rule ID'),
            'width' => '50px',
            'index' => 'rule_id',
            'type' => 'number',
            'sortable' => false,
            'filter' => false,
        ));

        $this->addColumn('label', array(
            'header' => $this->helper->__('Label'),
            'index' => 'label',
            'type' => 'text',
        ));

        $this->addColumn('email_value', array(
            'header' => $this->helper->__('Email Value'),
            'index' => 'email_value',
            'type' => 'text',
        ));

        $this->addColumn('ip_value', array(
            'header' => $this->helper->__('IP Value'),
            'index' => 'ip_value',
            'type' => 'text',
        ));

        $sourceOptions = array(
            0 => 'Disabled',
            1 => 'Enabled',
        );

        $this->addColumn('status', array(
            'header' => $this->helper->__('Status'),
            'index' => 'status',
            'options' => $sourceOptions,
            'type' => 'options',
        ));

        $this->addColumn('actions', array(
            'header' => $this->helper->__('Action'),
            'type' => 'action',
            'getter' => 'getRuleId',
            'actions' => $this->getActionArray(),
            'filter' => false,
            'sortable' => false,
        ));

        return parent::_prepareColumns();
    }

    /**
     * @return array
     */
    protected function getActionArray()
    {
        return array(
            array(
                'caption' => $this->helper->__('Edit'),
                'url' => array('base' => 'adminhtml/salesblockrule/edit'),
                'field' => 'rule_id'
            ),
            array(
                'caption' => $this->helper->__('Delete'),
                'url' => array('base' => 'adminhtml/salesblockrules/delete'),
                'field' => 'rule_id'
            ),
            array(
                'caption' => $this->helper->__('Disable'),
                'url' => array('base' => 'adminhtml/salesblockrules/disable'),
                'field' => 'rule_id'
            ),
            array(
                'caption' => $this->helper->__('Enable'),
                'url' => array('base' => 'adminhtml/salesblockrules/enable'),
                'field' => 'rule_id'
            )
        );
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
            'label' => Mage::helper('salesblock')->__('Delete'),
            'url' => $this->getUrl('adminhtml/salesblockrules/delete'),
        ));

        $this->getMassactionBlock()->addItem('disable', array(
            'label' => Mage::helper('salesblock')->__('Disable'),
            'url' => $this->getUrl('adminhtml/salesblockrules/disable'),
        ));

        $this->getMassactionBlock()->addItem('enable', array(
            'label' => Mage::helper('salesblock')->__('Enable'),
            'url' => $this->getUrl('adminhtml/salesblockrules/enable'),
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
