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
 * Observer model
 *
 * @category Bambuzo
 * @package Bambuzo_Productremover
 * @author Ultimate Module Creator
 */
class Bambuzo_Productremover_Model_Observer extends Mage_Core_Model_Abstract
{

    /**
     */
    public function notify ()
    {
        $count = $this->checkMessage();
        
        Mage::helper('bambuzo_productremover')->setNotViewedProductsCount(
                $count);
        
        /*
         * $mail = Mage::getModel('core/email');
         * $mail->setToName('John Customer');
         * $mail->setToEmail('geiser@onerhino.com');
         * $mail->setBody(
         * "Hi, there are $count products not viewed in the last 90 days. Please
         * check it and trigger the removal in admin panel.");
         * $mail->setSubject('Products not viewed to remove');
         * $mail->setFromEmail(
         * Mage::getStoreConfig('trans_email/ident_general/email'));
         * $mail->setFromName(
         * Mage::getStoreConfig('trans_email/ident_general/name'));
         * $mail->setType('text');
         *
         * try {
         * $mail->send();
         * } catch (Exception $e) {
         * Mage::logException($e);
         * }
         */
        
        return $count;
    }

    /**
     */
    public function checkMessage ()
    {
        $collection = Mage::getSingleton(
                'bambuzo_productremover/notviewedproduct')->getCollection();
        
        Mage::log('Refreshing not viewed product count...');
        Mage::log((string)$collection->getSelect());
        
        $count = $collection->getSize();
        
        Mage::log('Done.');

        return $count;
    }
}