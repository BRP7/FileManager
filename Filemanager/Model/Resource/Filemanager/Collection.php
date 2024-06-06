<?php
class Ccc_Filemanager_Model_Resource_Filemanager_Collection extends Varien_Data_Collection
{
    protected $_files = [];

    public function __construct()
    {
        parent::__construct();
        // Load your files into the collection
        $this->_loadFiles();
    }

    protected function _loadFiles()
    {
        // Implement your logic to load files into the collection
        // For example, reading files from a directory
        $directory = Mage::getBaseDir('var') . DS . 'log';
        $files = scandir($directory);

        foreach ($files as $file) {
            if (is_file($directory . '/' . $file)) {
                $item = new Varien_Object();
                $item->setData('filename', $file);
                $this->addItem($item);
            }
        }
    }

    protected function _renderFilters()
    {
        if ($this->_isFiltersRendered) {
            return;
        }

        foreach ($this->_filters as $filter) {
            $field = $filter['field'];
            $value = $filter['value'];
            $type = $filter['type'];

            $filteredItems = [];
            foreach ($this->_items as $item) {
                if ($type == 'like' && stripos($item->getData($field), trim($value, '%')) !== false) {
                    $filteredItems[] = $item;
                } elseif ($item->getData($field) == $value) {
                    $filteredItems[] = $item;
                }
            }
            $this->_items = $filteredItems;
        }

        $this->_isFiltersRendered = true;
    }

    protected function _renderOrders()
    {
        foreach ($this->_orders as $field => $direction) {
            usort($this->_items, function ($a, $b) use ($field, $direction) {
                if ($direction == self::SORT_ORDER_ASC) {
                    return strcmp($a->getData($field), $b->getData($field));
                } else {
                    return strcmp($b->getData($field), $a->getData($field));
                }
            });
        }
    }

    protected function _renderLimit()
    {
        if ($this->_pageSize) {
            $start = ($this->_curPage - 1) * $this->_pageSize;
            $this->_items = array_slice($this->_items, $start, $this->_pageSize);
        }
    }

    public function loadData($printQuery = false, $logQuery = false)
    {
        $this->_renderFilters();
        $this->_renderOrders();
        $this->_renderLimit();
        $this->_setIsLoaded(true);

        return $this;
    }
}
