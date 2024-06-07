<?php

class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_folderPath;

    public function __construct()
    {
        parent::__construct();
        $this->setId('filemanagerGrid');
        $this->setDefaultSort('filename'); // Set a default sort field
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $path = base64_decode($this->getRequest()->getParam('path'));
        $fullPath = Mage::getBaseDir() . DS . $path;
        Mage::log('Full path: ' . $fullPath, null, 'collection.log');
    
        if ($path) {
            $collection = Mage::getModel('ccc_filemanager/filemanager')
                ->addTargetDir($fullPath)
                ->setCollectRecursively(true)
                ->setDirsFilter('')
                ->setFilesFilter('');
            Mage::log('Collection set in _prepareCollection', null, 'collection.log');
            $this->setCollection($collection);
        }
        return parent::_prepareCollection();
    }
    

   

    protected function _prepareColumns()
    {
        $this->addColumn(
            'created_date',
            array(
                'header' => Mage::helper('ccc_filemanager')->__('Created Date'),
                'index' => 'created_date',
                'type' => 'datetime',
                'align' => 'center',
                'filter'=>false,
                'renderer' => 'Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_CreatedDate'
            )
        );

        $this->addColumn(
            'folder_name',
            array(
                'header' => Mage::helper('ccc_filemanager')->__('Folder Path'),
                'index' => 'dirname',
                'align' => 'left',
                'filter_condition_callback' => array($this, '_filterFolderPathCallback')
            )
        );

        $this->addColumn(
            'filename',
            array(
                'header' => Mage::helper('ccc_filemanager')->__('Filename'),
                'index' => 'basename',
                'editable' => true,
                'align' => 'left',
                'renderer' => 'Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Editable',
            )
        );

        $this->addColumn(
            'extension',
            array(
                'header' => Mage::helper('ccc_filemanager')->__('Extension'),
                'index' => 'extension',
                'align' => 'left',
            )
        );

        $this->addColumn('action', [
            'header' => Mage::helper('ccc_filemanager')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getFilePath',
            'renderer' => 'Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Action',
            'filter' => false,
            'sortable' => false,
            'is_system' => true,
        ]);

        return parent::_prepareColumns();
    }

   
    public function setFolderPath($folderPath)
    {
        $this->_folderPath = $folderPath;
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getFilename($row)
    {
        return $row->getData('filename');
    }
}
