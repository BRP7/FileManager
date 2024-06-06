<?php
class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_CreatedDate extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $date = $row->getData($this->getColumn()->getIndex());
        return Mage::helper('core')->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, true);
    }
}
