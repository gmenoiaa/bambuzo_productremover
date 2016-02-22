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
 * Productremover default helper
 *
 * @category Bambuzo
 * @package Bambuzo_Productremover
 * @author Ultimate Module Creator
 */
class Bambuzo_Productremover_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * convert array to options
     *
     * @access public
     * @param
     *            $options
     * @return array
     * @author Ultimate Module Creator
     */
    public function convertOptions ($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && ! is_array($option['value']) &&
                     isset($option['label']) && ! is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    /**
     *
     * @param $collection Varien_Data_Collection            
     * @param array $callback            
     */
    public function walk ($collection, $callback, $callbackAfter = null, 
            $batchSize = 1000)
    {
        $total = $collection->getSize();
        $currentOffset = 0;
        $response = [];
        do {
            $partialResponse = [];
            $collection->getSelect()->limit($batchSize, $currentOffset);
            $collection->load();
            foreach ($collection as $item) {
                $itemResponse = call_user_func($callback, $item);
                if ($itemResponse != null) {
                    $response[] = $itemResponse;
                    $partialResponse[] = $itemResponse;
                }
            }
            if ($callbackAfter) {
                call_user_func($callbackAfter, $partialResponse);
            }
            $currentOffset += $batchSize;
            $collection->clear();
        } while ($currentOffset < $total);
        
        return empty($response) ? false : $response;
    }
}
