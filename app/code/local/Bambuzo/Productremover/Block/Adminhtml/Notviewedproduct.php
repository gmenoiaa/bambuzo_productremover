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
 * Not Viewed Product admin block
 *
 * @category    Bambuzo
 * @package     Bambuzo_Productremover
 * @author      Ultimate Module Creator
 */
class Bambuzo_Productremover_Block_Adminhtml_Notviewedproduct extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_notviewedproduct';
        $this->_blockGroup         = 'bambuzo_productremover';
        parent::__construct();
        $this->_headerText         = Mage::helper('bambuzo_productremover')->__('Not Viewed Product');
        $this->removeButton('add');

    }
}
