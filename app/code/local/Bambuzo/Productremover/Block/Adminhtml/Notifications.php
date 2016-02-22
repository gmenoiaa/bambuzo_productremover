<?php

class Bambuzo_Productremover_Block_Adminhtml_Notifications extends Mage_Adminhtml_Block_Template
{

    public function getMessage ()
    {
        $session = Mage::getSingleton('core/session');
        $count = $session->getNotviewedproductCount();
        
        if ($count == null) {
            $count = Mage::getSingleton('bambuzo_productremover/observer')->checkMessage();
            $session->setNotviewedproductCount($count);
        }
        
        $message = null;
        if ($count > 0) {
            $url = $this->getUrl('*/productremover_notviewedproduct/index');
            $message = "There are $count products not viewed in the last 90 days. Please go to <a href=\"$url\">Product Remover</a> page and trigger the removal in admin panel.";
        }
        
        return $message;
    }
}