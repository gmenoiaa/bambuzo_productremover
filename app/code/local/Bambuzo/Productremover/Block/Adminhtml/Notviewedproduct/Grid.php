<?php

/**
 * Bambuzo_Productremover extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Bambuzo
 * @package        Bambuzo_Productremover
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Not Viewed Product admin grid block
 *
 * @category Bambuzo
 * @package Bambuzo_Productremover
 * @author Ultimate Module Creator
 */
class Bambuzo_Productremover_Block_Adminhtml_Notviewedproduct_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    private $productIds = array();
    
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct ()
    {
        parent::__construct();
        $this->setId('notviewedproductGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    
    /**
     * 
     * @param unknown $productIds
     */
    public function withProductIds($productIds) 
    {
        $this->productIds = $productIds;
        return $this;
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Bambuzo_Productremover_Block_Adminhtml_Notviewedproduct_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection ()
    {
        $collection = Mage::getModel('bambuzo_productremover/notviewedproduct')
                            ->getCollection($this->productIds);
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Bambuzo_Productremover_Block_Adminhtml_Notviewedproduct_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns ()
    {
        $this->addColumn('entity_id', 
                array(
                        'header' => Mage::helper('bambuzo_productremover')->__(
                                'Id'),
                        'index' => 'entity_id',
                        'type' => 'number'
                ));
        
        $this->addColumn('sku', 
                array(
                        'header' => Mage::helper('bambuzo_productremover')->__(
                                'SKU'),
                        'width' => '150px',
                        'align' => 'left',
                        'index' => 'sku'
                ));
        
        $this->addColumn('name', 
                array(
                        'header' => Mage::helper('bambuzo_productremover')->__(
                                'Name'),
                        'align' => 'left',
                        'index' => 'name'
                ));
        
        $this->addColumn('qty', 
                array(
                        'header' => Mage::helper('bambuzo_productremover')->__(
                                'Qty'),
                        'index' => 'qty',
                        'type' => 'number'
                ));
        
        $this->addColumn('status', 
                array(
                        'header' => Mage::helper('catalog')->__('Status'),
                        'width' => '70px',
                        'index' => 'status',
                        'type' => 'options',
                        'options' => Mage::getSingleton(
                                'catalog/product_status')->getOptionArray()
                ));
        
        $this->addExportType('*/*/exportCsv', 
                Mage::helper('bambuzo_productremover')->__('CSV'));
        $this->addExportType('*/*/exportExcel', 
                Mage::helper('bambuzo_productremover')->__('Excel'));
        $this->addExportType('*/*/exportXml', 
                Mage::helper('bambuzo_productremover')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Bambuzo_Productremover_Block_Adminhtml_Notviewedproduct_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction ()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('notviewedproduct');
        $this->getMassactionBlock()->addItem('delete', 
                array(
                        'label' => Mage::helper('bambuzo_productremover')->__(
                                'Delete'),
                        'url' => $this->getUrl('*/*/massDelete'),
                        'confirm' => Mage::helper('bambuzo_productremover')->__(
                                'Are you sure?')
                ));
        return $this;
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl ()
    {
        return $this->getUrl('*/*/grid', 
                array(
                        '_current' => true
                ));
    }
}
