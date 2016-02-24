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
        $this->_delete = $this->getArg('delete') != null;
        
        if ($this->getArg('notviewed') != null) {
            $collection = Mage::getSingleton(
                    'bambuzo_productremover/notviewedproduct')->getCollection();
            $count = $collection->getSize();
            
            echo "Found $count products to delete\n";
            
            if ($this->_delete) {
                Mage::helper('bambuzo_productremover')->walk($collection, 
                        function  ($product)
                        {
                            return $product->getId();
                        }, 
                        function  ($ids)
                        {
                            
                            echo "Deleting " . count($ids) . " products..\n";
                            $model = Mage::getSingleton(
                                    'bambuzo_productremover/productremover');
                            $model->setDebug(true);
                            $model->deleteProducts($ids);
                        });
            }
            
            echo "Done.\n";
        } else 
            if ($this->getArg('file')) {
                
                $file = $this->getArg('file');
                
                $skus = Mage::getSingleton(
                        'bambuzo_productremover/productremover')->processCsv(
                        $file);
                
                echo "Found " . count($skus) . " lines\n";
                
                if ($this->_delete) {
                    echo "Deleting products...\n";
                    
                    $model = Mage::getSingleton(
                            'bambuzo_productremover/productremover');
                    $model->setDebug(true);
                    
                    list ($file, $rows) = $model->deleteProductsBySku($skus);
                    
                    echo "Created log file $file\n";
                    echo "Deleted total $rows products\n";
                }
                echo "Done.\n";
            } else {
                echo $this->usageHelp();
            }
    }

    /**
     * Retrieve Usage Help Message
     */
    public function usageHelp ()
    {
        return <<<USAGE
Usage:  php -f productremover.php -- [options]
    
  --notviewed                   Deletes all not viewed products in last 90 days
  --file                        Deletes products from a CSV file with skus
  --delete                      Set this if you're sure want to remove
  help                          This help
    
USAGE;
    }
}

$shell = new Bambuzo_Productremover_Shell_Productremover();
$shell->run();
