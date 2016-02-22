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
 * Not Viewed Product model
 *
 * @category Bambuzo
 * @package Bambuzo_Productremover
 * @author Ultimate Module Creator
 */
class Bambuzo_Productremover_Model_Notviewedproduct extends Mage_Core_Model_Abstract
{

    public function getCollection ($productIds = array())
    {
        $from = date('Y-m-d H:i:s', strtotime('-90 days'));
        $viewedProducts = $this->findViewedProductIds($from);
        
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('status');
        
        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty', 'cataloginventory/stock_item', 'qty', 
                    'product_id=entity_id', '{{table}}.stock_id=1', 'left');
        }
        
        if ($productIds) {
            $collection->addFieldToFilter('entity_id', 
                    array(
                            'in' => $productIds
                    ));
        } else {
            $collection->addFieldToFilter('entity_id', 
                    array(
                            'nin' => $viewedProducts
                    ));
        }
        
        return $collection;
    }
    
    /**
     * 
     * @param unknown $productIds
     */
    public function deleteProducts($notviewedproductIds) 
    {
        $file = $this->saveCsvFile($notviewedproductIds);
        
        foreach ($notviewedproductIds as $notviewedproductId) {
            $product = Mage::getSingleton('catalog/product')->load($notviewedproductId);
            Mage::dispatchEvent('catalog_controller_product_delete', array('product' => $product));
            $product->delete();
        }
        
        return $file;
    }

    /**
     *
     * @param unknown $ids            
     */
    private function saveCsvFile ($ids)
    {
        $file = Mage::getSingleton('core/layout')->createBlock(
                'bambuzo_productremover/adminhtml_notviewedproduct_grid')
            ->withProductIds($ids)
            ->getCsvFile();
        
        $srcFile = $file['value'];

        return $srcFile;
    }

    /**
     *
     * @param unknown $from            
     */
    private function findViewedProductIds ($from)
    {
        $collection = Mage::getResourceModel('reports/product_collection');
        $collection->addViewsCount($from, now());
        
        $viewedProducts = Mage::helper('bambuzo_productremover')->walk(
                $collection, 
                function  ($product)
                {
                    return $product->getId();
                });
        
        return $viewedProducts;
    }
}