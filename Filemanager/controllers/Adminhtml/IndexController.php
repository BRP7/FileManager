<?php

class Ccc_Filemanager_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('ccc_filemanager/manage');
        $this->_addContent($this->getLayout()->createBlock('core/template')->setTemplate('filemanager/index.phtml'));
        $this->renderLayout();
    }
    public function gridAction()
    {
        Mage::log('Grid action triggered', null, 'custom.log');
        $this->loadLayout();
        $block = $this->getLayout()->createBlock('ccc_filemanager/adminhtml_filemanager_grid');
        Mage::log('Block created: ' . get_class($block), null, 'custom.log');
        $html = $block->toHtml();
        Mage::log($html, null, 'custom.log');
        $this->getResponse()->setBody($html);
    }

    public function renameFileAction()
    {
        $oldFilename = $this->getRequest()->getParam('oldFilename');
        Mage::log($this->getRequest()->isPost(), null, 'custom_post.log');
        $response = array('status' => 'error', 'message' => 'Invalid request');
    
        if ($this->getRequest()->isPost()) {
            $newFilename = $this->getRequest()->getParam('newFilename');
            $filePath = $this->getRequest()->getParam('filePath');
    
            Mage::log("Old Filename: $oldFilename, New Filename: $newFilename, File Path: $filePath", null, 'custom.log');
    
            try {
                $fileManagerModel = Mage::getModel('ccc_filemanager/filemanager');
                $result = $fileManagerModel->renameCustomFile($filePath, $oldFilename, $newFilename);
    
                if ($result) {
                    $response = array('status' => 'success', 'message' => 'File renamed successfully');
                } else {
                    $response['message'] = 'Failed to rename the file';
                }
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }
    


    public function deleteAction()
    {
        $fullPath = urldecode($this->getRequest()->getParam('fullPath'));
        try {
            if (is_file($fullPath)) {
                unlink($fullPath);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ccc_filemanager')->__('File was successfully deleted.'));
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ccc_filemanager')->__('File does not exist.'));
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/grid');
    }

    public function downloadAction()
    {
        $fullPath = urldecode($this->getRequest()->getParam('fullPath'));
        // print_r($fullPath);die;
        if (file_exists($fullPath)) {
            $this->_prepareDownloadResponse(basename($fullPath), file_get_contents($fullPath), 'application/octet-stream');
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ccc_filemanager')->__('File does not exist.'));
            $this->_redirect('*/*/load');
        }
    }

    // public function downloadAction()
    // {
    //     $filename = $this->getRequest()->getParam('filename');
    //     $filePath = $this->getRequest()->getParam('filePath');
    //     $fullPath = $filePath.'//'.$filename;

    //     if ($fullPath && file_exists($fullPath)) {
    //         $this->_prepareDownloadResponse(basename($fullPath), file_get_contents($fullPath));
    //     } else {
    //         Mage::getSingleton('adminhtml/session')->addError('File not found');
    //         $this->_redirect('*/*/');
    //     }
    // }

    // public function deleteAction()
    // {
    //     $filename = $this->getRequest()->getParam('filename');
    //     $filePath = $this->getRequest()->getParam('filePath');
    //     $fullPath = $filePath.'//'.$filename;

    //     if ($filePath && file_exists($filePath)) {
    //         unlink($fullPath);
    //         Mage::getSingleton('adminhtml/session')->addSuccess('File deleted successfully');
    //     } else {
    //         Mage::getSingleton('adminhtml/session')->addError('File not found');
    //     }

    //     $this->_redirect('*/*/');
    // }



}
