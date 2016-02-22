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
 * Not Viewed Product admin controller
 *
 * @category    Bambuzo
 * @package     Bambuzo_Productremover
 * @author      Ultimate Module Creator
 */
class Bambuzo_Productremover_Adminhtml_Productremover_NotviewedproductController extends Bambuzo_Productremover_Controller_Adminhtml_Productremover
{
    /**
     * init the not viewed product
     *
     * @access protected
     * @return Bambuzo_Productremover_Model_Notviewedproduct
     */
    protected function _initNotviewedproduct()
    {
        $notviewedproductId  = (int) $this->getRequest()->getParam('id');
        $notviewedproduct    = Mage::getModel('bambuzo_productremover/notviewedproduct');
        if ($notviewedproductId) {
            $notviewedproduct->load($notviewedproductId);
        }
        Mage::register('current_notviewedproduct', $notviewedproduct);
        return $notviewedproduct;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        
        $text = <<<TEXT
If deleting products from this page takes too much time and fails, you can execute this from a cli command directly in the server. See below:
             
<pre>
                
    php shell/productremover.php --delete
                
</pre>
                
TEXT;
        Mage::getSingleton('core/session')->addWarning($text);
        
        $this->loadLayout();
        $this->_title(Mage::helper('bambuzo_productremover')->__('Product Remover'))
             ->_title(Mage::helper('bambuzo_productremover')->__('Not Viewed Products'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * mass delete not viewed product - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $notviewedproductIds = $this->getRequest()->getParam('notviewedproduct');
        if (!is_array($notviewedproductIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bambuzo_productremover')->__('Please select not viewed products to delete.')
            );
        } else {
            try {
                $file = Mage::getSingleton('bambuzo_productremover/notviewedproduct')->deleteProducts($notviewedproductIds);
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bambuzo_productremover')->__('Total of %d not viewed products were successfully deleted. See products at %s', count($notviewedproductIds), $file)
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bambuzo_productremover')->__('There was an error deleting not viewed products.')
                );  
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    
    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'notviewedproduct.csv';
        $content    = $this->getLayout()->createBlock('bambuzo_productremover/adminhtml_notviewedproduct_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'notviewedproduct.xls';
        $content    = $this->getLayout()->createBlock('bambuzo_productremover/adminhtml_notviewedproduct_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'notviewedproduct.xml';
        $content    = $this->getLayout()->createBlock('bambuzo_productremover/adminhtml_notviewedproduct_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/bambuzo_productremover/notviewedproduct');
    }
}
