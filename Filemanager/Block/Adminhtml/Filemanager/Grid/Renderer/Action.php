<?php
class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $actions = [];

        $fileName = $row->getData('filename');
        $folderName = $row->getData('folder_name');
        $fullPath = Mage::getBaseDir() . DS . $folderName . DS . $fileName;
        // $fullPath = $folderName . DS . $fileName;

        // Encode full path for URL
        $fullPathEncoded = urlencode($fullPath);

        // Delete Action
        $actions[] = [
            'url' => $this->getUrl('*/*/delete', ['fullPath' => $fullPathEncoded]),
            'caption' => Mage::helper('ccc_filemanager')->__('Delete'),
            'confirm' => Mage::helper('ccc_filemanager')->__('Are you sure you want to delete this file?')
        ];

        // Download Action
        $actions[] = [
            'url' => $this->getUrl('*/*/download', ['fullPath' => $fullPathEncoded]),
            'caption' => Mage::helper('ccc_filemanager')->__('Download')
        ];

        $this->getColumn()->setActions($actions);

        return parent::render($row);
    }
}
