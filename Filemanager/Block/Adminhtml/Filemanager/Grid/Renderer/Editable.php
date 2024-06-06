<?php

class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Editable extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    
        public function render(Varien_Object $row)
        {
           $fullPath =  Mage::getBaseDir().$row->getFolderName();
            $value = $row->getData($this->getColumn()->getIndex());
            $url = $this->getUrl('*/*/rename');
            $html = '<div class="editableDiv" data-filepath="' . $fullPath . '"  data-url="' . $url . '"data-value="' . $value . '" data-id="' . $row->getId() . '">' . $value . '</div>';
            return $html;
        }
    }
