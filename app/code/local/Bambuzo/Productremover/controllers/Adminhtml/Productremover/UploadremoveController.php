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
 * @category Bambuzo
 * @package Bambuzo_Productremover
 * @author Ultimate Module Creator
 */
class Bambuzo_Productremover_Adminhtml_Productremover_UploadremoveController extends Bambuzo_Productremover_Controller_Adminhtml_Productremover
{

    public function indexAction ()
    {
        $this->loadLayout();
        $this->_title(
                Mage::helper('bambuzo_productremover')->__('Product Remover'))
            ->_title(
                Mage::helper('bambuzo_productremover')->__('Upload Remove'));
        $this->renderLayout();
    }

    public function uploadAction ()
    {
        if ($data = $this->getRequest()->getPost() &&
                 isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
            
            $delete = isset($_POST['is_delete']) ? boolval($_POST['is_delete']) : false;
            
            try {
                
                $path = Mage::getBaseDir() . DS . 'var' . DS . 'import' . DS .
                         'productremover';
                $fname = $_FILES['file']['name'];
                $fullname = $path . DS . $fname;
                
                $uploader = new Varien_File_Uploader('file');
                $uploader->setAllowedExtensions(
                        array(
                                'CSV',
                                'csv'
                        ));
                $uploader->setAllowCreateFolders(true);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $uploader->save($path, $fname);
                
                $skus = Mage::getSingleton(
                        'bambuzo_productremover/productremover')->processCsv(
                        $fullname);
                
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('bambuzo_productremover')->__(
                                'File uploaded successfully'));
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('bambuzo_productremover')->__(
                                'Found %d lines', count($skus)));
                
                if ($delete) {
                    list ($file, $rows) = Mage::getSingleton(
                            'bambuzo_productremover/productremover')->deleteProductsBySku(
                            $skus);
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                            Mage::helper('bambuzo_productremover')->__(
                                    'Created log file %s', $file));
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                            Mage::helper('bambuzo_productremover')->__(
                                    'Deleted total %d products', $rows));
                } else {
                    Mage::getSingleton('adminhtml/session')->addNotice(
                            Mage::helper('bambuzo_productremover')->__(
                                    'Check the delete box to confirm removal'));
                }
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('bambuzo_productremover')->__(
                                'Error uploading file: ' . $e->getMessage()));
            }
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bambuzo_productremover')->__(
                            'Something went wrong when uploading'));
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed ()
    {
        return Mage::getSingleton('admin/session')->isAllowed(
                'catalog/bambuzo_productremover/notviewedproduct');
    }
}
