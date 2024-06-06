<?php

class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_folderPath;

    public function __construct()
    {
        parent::__construct();
        $this->setId('filemanagerGrid');
        $this->setDefaultSort('name');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $path = $this->getRequest()->getParam('path');
        // var_dump($path);
        // var_dump(Mage::getBaseDir().DS.$path);
        $folderPath = Mage::getBaseDir() . DS . trim($this->_folderPath, '/');
        if ($path != null) {
            $folderPath = $folderPath . $path;
            $collection = Mage::getModel('ccc_filemanager/filemanager')
                ->addTargetDir($folderPath)
                ->setCollectRecursively(true)
                ->loadData();
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
                'index' => 'mtime',
                'type' => 'datetime',
                'align' => 'center',
                'renderer' => 'Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_CreatedDate'
            )
        );

        $this->addColumn(
            'folder_name',
            array(
                'header' => Mage::helper('ccc_filemanager')->__('Folder Path'),
                'index' => 'folder_name',
                'align' => 'left',
                'filter_condition_callback' => array($this, '_filterFolderPathCallback')
            )
        );

        $this->addColumn(
            'filename',
            array(
                'header' => Mage::helper('ccc_filemanager')->__('Filename'),
                'index' => 'filename',
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

        $this->addColumn(
            'action',
            array(
                'header' => Mage::helper('ccc_filemanager')->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getFilename',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('ccc_filemanager')->__('Download'),
                        'url' => array('base' => '*/*/download', 'params' => array('filename' => '$filename')),
                        'field' => 'filename',
                        'data-filepath' => 'filepath', // Ensure filePath is included here
                    ),
                    array(
                        'caption' => Mage::helper('ccc_filemanager')->__('Delete'),
                        'url' => array('base' => '*/*/delete', 'params' => array('filename' => '$filename')),
                        'field' => 'filename',
                        'data-filepath' => 'filepath', // Ensure filePath is included here
                    ),
                ),
                'filter' => false,
                'sortable' => false,
                'is_system' => true,
            )
        );


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

    protected function _addColumnFilterToCollection($column)
    {
        if (!$this->getCollection()) {
            return $this;
        }

        $filter = $column->getFilter()->getValue();
        if (!$filter) {
            return $this;
        }

        $collection = $this->getCollection();
        $columnName = $column->getId();

        if ($columnName === 'created_date') {
            $fromDate = isset($filter['from']) ? strtotime($filter['from']) : null;
            $toDate = isset($filter['to']) ? strtotime($filter['to']) : null;

            $filteredItems = [];
            foreach ($collection as $item) {
                $createdDate = strtotime($item->getCreatedDate());

                if (($fromDate === null || $createdDate >= $fromDate) && ($toDate === null || $createdDate <= $toDate)) {
                    $filteredItems[] = $item;
                }
            }

            $filteredCollection = new Varien_Data_Collection();
            foreach ($filteredItems as $item) {
                $filteredCollection->addItem($item);
            }

            $this->setCollection($filteredCollection);
        } else {
            $filteredItems = [];
            foreach ($collection as $item) {
                $addItem = false;
                switch ($columnName) {
                    case 'file_name':
                        if (stripos($item->getFileName(), $filter) !== false) {
                            $addItem = true;
                        }
                        break;
                    case 'folder_path':
                        if (stripos($item->getFolderPath(), $filter) !== false) {
                            $addItem = true;
                        }
                        break;
                    case 'file_extension':
                        if (stripos($item->getFileExtension(), $filter) !== false) {
                            $addItem = true;
                        }
                        break;
                }
                if ($addItem) {
                    $filteredItems[] = $item;
                }
            }

            $filteredCollection = new Varien_Data_Collection();
            foreach ($filteredItems as $item) {
                $filteredCollection->addItem($item);
            }

            $this->setCollection($filteredCollection);
        }

        return $this;
    }
}
