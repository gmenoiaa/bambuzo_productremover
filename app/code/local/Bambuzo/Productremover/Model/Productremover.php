<?php

class Bambuzo_Productremover_Model_Productremover extends Mage_Core_Model_Abstract
{

    public function processCsv ($file)
    {
        $csvObject = new Varien_File_Csv();
        $lines = $csvObject->getData($file);
        
        $skuIndex = null;
        
        foreach ($lines[0] as $index => $column) {
            if (strtolower($column) == 'sku') {
                $skuIndex = $index;
                break;
            }
        }
        
        unset($lines[0]);
        
        $skus = array();
        foreach ($lines as $line) {
            if (isset($line[$skuIndex]) && $line[$skuIndex]) {
                $skus[] = $line[$skuIndex];
            }
        }
        
        return $skus;
    }

    /**
     *
     * @param unknown $productIds            
     */
    public function deleteProducts ($ids)
    {
        $file = $this->saveCsvFile($ids, null);
        
        $parsedIds = array();
        foreach ($ids as $id) {
            $parsedIds[] = "'$id'";
        }
        
        $table = Mage::getSingleton('core/resource')->getTableName(
                'catalog/product');
        
        $query = "DELETE FROM `$table` WHERE `entity_id` IN (" .
                 implode(',', $parsedIds) . ")";
        
        $count = $this->executeQuery($query);
        return array(
                $file,
                $count
        );
    }

    /**
     *
     * @param unknown $productIds            
     */
    public function deleteProductsBySku ($skus)
    {
        $file = $this->saveCsvFile(null, $skus);
        
        $total = count($skus);
        $max = 10;
        $pages = ceil($total / $max);
        $offset = 0;
        
        do {
            $subset = array_slice($skus, $offset, $max);
            
            if (empty($subset)) {
                break;
            }
            
            $parsedSkus = array();
            foreach ($subset as $sku) {
                $parsedSkus[] = "'$sku'";
            }
            
            $table = Mage::getSingleton('core/resource')->getTableName(
                    'catalog/product');
            
            $query = "DELETE FROM `$table` WHERE `sku` IN (" .
                     implode(',', $parsedSkus) . ")";
            
            $count += $this->executeQuery($query);
            $offset += $max;
        } while (true);
        
        return array(
                $file,
                $count
        );
    }

    private function executeQuery ($query)
    {
        if ($this->getDebug()) {
            echo "$query\n";
        }
        
        $rows = Mage::getSingleton('core/resource')->getConnection('core_write')->exec(
                $query);
        
        return $rows;
    }

    /**
     *
     * @param unknown $ids            
     */
    private function saveCsvFile ($ids = null, $skus = null)
    {
        $file = Mage::getSingleton('core/layout')->createBlock(
                'bambuzo_productremover/adminhtml_productremover_grid')
            ->withProductIds($ids)
            ->withProductSkus($skus)
            ->getCsvFile();
        
        $srcFile = $file['value'];
        
        $io = new Varien_Io_File();
        
        $path = Mage::getBaseDir('var') . DS . 'export' . DS . 'productremover';
        $name = md5(microtime());
        $file = $path . DS . $name . '.csv';
        
        $io->setAllowCreateFolders(true);
        $io->open(array(
                'path' => $path
        ));
        $io->streamOpen($file, 'w+');
        $io->streamLock(true);
        $io->streamWrite(file_get_contents($srcFile));
        $io->streamUnlock();
        $io->streamClose();
        
        unlink($srcFile);
        
        return $file;
    }
}