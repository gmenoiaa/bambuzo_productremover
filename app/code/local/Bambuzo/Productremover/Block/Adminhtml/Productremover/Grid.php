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
class Bambuzo_Productremover_Block_Adminhtml_Productremover_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    private $_productIds = array();

    private $_productSkus = array();

    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct ()
    {
        parent::__construct();
        $this->setId('productremoverGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
    }

    /**
     *
     * @param unknown $productIds            
     */
    public function withProductIds ($productIds)
    {
        $this->_productIds = $productIds;
        return $this;
    }

    /**
     *
     * @param unknown $productSkus            
     */
    public function withProductSkus ($productSkus)
    {
        $this->_productSkus = $productSkus;
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
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('status');
        
        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty', 'cataloginventory/stock_item', 'qty', 
                    'product_id=entity_id', '{{table}}.stock_id=1', 'left');
        }
        
        if (! empty($this->_productIds)) {
            $collection->addFieldToFilter('entity_id', 
                    array(
                            'in' => $this->_productIds
                    ));
        } else 
            if (!empty($this->_productSkus)) {
                $collection->addFieldToFilter('sku', 
                        array(
                                'in' => $this->_productSkus
                        ));
            } else {
                throw new Exception(
                        'Any of productIds or productSkus should be set');
            }
        
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
        
        return parent::_prepareColumns();
    }
}
