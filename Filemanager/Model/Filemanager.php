<?php

class Ccc_Filemanager_Model_Filemanager extends Varien_Data_Collection_Filesystem
{
    protected function _generateRow($filepath)
    {
        $row = parent::_generateRow($filepath);
        $row['basename'] = basename($filepath);
        $row['folder_name'] = dirname($filepath);
        $row['created_date'] = $this->_getFormattedDate(filectime($filepath));
        $row['extension'] = pathinfo($filepath, PATHINFO_EXTENSION);
        $basePath = Mage::getBaseDir().DS;
        $row['dirname'] = str_replace($basePath, "", $row['folder_name']);
        return $row;
    }

    protected function _getFormattedDate($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }

    public function renameCustomFile($filePath, $oldFilename, $newFilename)
    {
        $oldFilePath = $filePath . DS . $oldFilename;
        $newFilePath = $filePath . DS . $newFilename;

        if (file_exists($oldFilePath)) {
            if (!file_exists($newFilePath)) {
                return rename($oldFilePath, $newFilePath);
            } else {
                throw new Exception('New filename already exists.');
            }
        } else {
            throw new Exception('Old file does not exist.');
        }
    }

    public function filterCallbackLike($field, $filterValue, $row)
    {
        $filterValue = trim($filterValue instanceof Zend_Db_Expr
            ? (string) $filterValue
            : $filterValue, "'");

        $filterValueRegex = str_replace('%', '(.*?)', preg_quote($filterValue, '/'));
        return (bool) preg_match("/^{$filterValueRegex}$/i", $row[$field]);
    }

   
}
