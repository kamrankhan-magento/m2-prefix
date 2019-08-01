<?php
class ZGeneric
{
    public function __construct()
    {
    }
    static function arrayColumn(array $elements,string ...  $columnNames)
    {
        $rows = [];
        foreach ($elements as $singleRow) {
            $singleConversion = [];
            foreach ($columnNames as $singleColumn) {
                $singleConversion[$singleColumn] = $singleRow[$singleColumn]??null;
            }
            $rows[] = $singleConversion;
        }
        return $rows;
    }
}