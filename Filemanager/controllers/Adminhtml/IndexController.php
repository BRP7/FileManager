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
        $this->loadLayout();
        $block = $this->getLayout()->createBlock('ccc_filemanager/adminhtml_filemanager_grid');
        $this->getResponse()->setBody($block->toHtml());
    }

    public function renameAction()
    {
        $response = array('status' => 'error', 'message' => 'Invalid request');
        
        if ($this->getRequest()->isPost()) {
            $oldFilename = $this->getRequest()->getParam('oldFilename');
            $newFilename = $this->getRequest()->getParam('newFilename');
            $filePath = $this->getRequest()->getParam('filePath');
            
            try {
                $fileManagerModel = Mage::getModel('ccc_filemanager/filemanager');
                $result = $fileManagerModel->renameFile($filePath, $oldFilename, $newFilename);
                
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

    
}
