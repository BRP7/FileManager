<?php
class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Extension extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column for file extension
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $extension = $row->getExtension();
        return $extension;
    }
}
