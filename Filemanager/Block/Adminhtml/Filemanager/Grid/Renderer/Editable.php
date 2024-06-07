<?php

class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Editable extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    
        public function render(Varien_Object $row)
        {
    
            $value = $row->getData($this->getColumn()->getIndex());
            $url = $this->getUrl('*/*/renameFile');
            $html = '<div class="editableDiv" data-filepath="' . $row->getFolderName(). '"  data-url="' . $url . '"data-value="' . $value . '" data-id="' . $row->getId() . '">' . $value . '</div>';
            return $html;
        }
    }
