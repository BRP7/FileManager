<?php

class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Editable extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    
        public function render(Varien_Object $row)
        {
            // echo 1212;
           $fullPath = $row->getFolderName();
            // var_dump($fullPath);
            $value = $row->getData($this->getColumn()->getIndex());
            // var_dump($value);
            $url = $this->getUrl('*/*/rename');
            $html = '<div class="editableDiv"   data-filepath="' . $fullPath . '"  data-url="' . $url . '"data-value="' . $value . '" data-id="' . $row->getId() . '">' . $value . '</div>';
            return $html;
        }
    }
