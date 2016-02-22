<?php
require_once 'abstract.php';

/**
 * 
 * @author geiser
 *
 */
class Bambuzo_Productremover_Shell_Productremover extends Mage_Shell_Abstract
{
    
    private $_delete;

    public function run ()
    {
        $collection = Mage::getSingleton('bambuzo_productremover/notviewedproduct')->getCollection();
        $count = $collection->getSize();
        
        echo "Found $count products to delete\n";
        
        $this->_delete = $this->getArg('delete') != null;
    
        Mage::helper('bambuzo_productremover')->walk($collection, 
            function  ($product)
            {
                return $product->getId();
            }, 
            function  ($ids)
            {
                echo "Deleting ".count($ids)." products..\n";
                if($this->_delete) {
                    Mage::getSingleton('bambuzo_productremover/notviewedproduct')->deleteProducts($ids);
                }
            });
        
        echo "Done.\n";
    }
}

$shell = new Bambuzo_Productremover_Shell_Productremover();
$shell->run();
