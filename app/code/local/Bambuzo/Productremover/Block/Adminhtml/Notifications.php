<?php

class Bambuzo_Productremover_Block_Adminhtml_Notifications extends Mage_Adminhtml_Block_Template
{

    public function getMessage ()
    {
        $count = Mage::helper('bambuzo_productremover')->getNotViewedProductsCount();            
        
        $message = null;
        if ($count > 0) {
            $url = $this->getUrl('*/productremover_notviewedproduct/index');
            $message = "There are $count products not viewed in the last 90 days. Please go to <a href=\"$url\">Product Remover</a> page and trigger the removal in admin panel.";
        }
        
        return $message;
    }
}