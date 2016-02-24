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
class Bambuzo_Productremover_Block_Adminhtml_Uploadremove_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _construct ()
    {
        parent::_construct();
        
        $form = new Varien_Data_Form(
                array(
                        'id' => 'edit_form',
                        'method' => 'post',
                        'enctype' => 'multipart/form-data',
                        'action' => $this->getUrl('*/*/upload', 
                                array(
                                        '_current' => true
                                ))
                ));
        
        $form->setUseContainer(true);
        $this->setForm($form);
        
        return $this;
    }

    protected function _prepareForm ()
    {
        $fieldset = $this->getForm()->addFieldset('my_el_form_fs111', 
                array(
                        'legend' => Mage::helper('bambuzo_productremover')->__(
                                'Legend Text')
                ));
        
        $fieldset->addField('file', 'file', 
                array(
                        'label' => Mage::helper('bambuzo_productremover')->__(
                                'CSV'),
                        'class' => 'disable',
                        'required' => true,
                        'name' => 'file'
                ));
        
        $fieldset->addField('is_delete', 'checkbox', array(
                'label'     => Mage::helper('bambuzo_productremover')->__('Delete?'),
                'onclick'   => 'this.value = this.checked ? 1 : 0;',
                'name'      => 'is_delete',
        ));
        
        return parent::_prepareForm();
    }
}