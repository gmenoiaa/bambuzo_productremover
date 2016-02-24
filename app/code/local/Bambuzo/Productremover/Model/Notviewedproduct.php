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

    public function getCollection ()
    {
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('status');
        
        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty', 'cataloginventory/stock_item', 'qty', 
                    'product_id=entity_id', '{{table}}.stock_id=1', 'left');
        }
        
        $from = date('Y-m-d H:i:s', strtotime('-90 days'));
        $now = date('Y-m-d H:i:s', time());
        
        $viewedProducts = "SELECT `e`.`entity_id` FROM `report_event` AS `report_table_views` ";
        $viewedProducts .= "INNER JOIN `catalog_product_entity` AS `e` ON e.entity_id = report_table_views.object_id AND e.entity_type_id = 4 ";
        $viewedProducts .= "WHERE (report_table_views.event_type_id = 1) ";
        $viewedProducts .= "AND (logged_at >= '$from') ";
        $viewedProducts .= "AND (logged_at <= '$now') ";
        $viewedProducts .= "GROUP BY `e`.`entity_id`";
        $viewedProducts .= "HAVING (COUNT(report_table_views.event_id) > 0) ";
        
        $collection->getSelect()->where(
                "e.entity_id not in ( $viewedProducts )");
        
        return $collection;
    }
}