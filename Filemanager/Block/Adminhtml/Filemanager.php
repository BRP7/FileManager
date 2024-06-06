<?php 

class Ccc_Filemanager_Block_Adminhtml_Filemanager extends  Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_filemanager';
        $this->_blockGroup = 'ccc_filemanager';
        $this->_headerText = Mage::helper('ccc_filemanager')->__('Manage Files');
        parent::__construct();
    }
}
