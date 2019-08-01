<?php
/**
 * To replace
 *
 * ProductEav
 *
 */
$magentoInc->setAdminHtml();

Class ProductEav
{

    protected $iProductId;

    protected $bShowAttributeId = false;
    protected $bShowTable = false;
    protected $rowId ;
    protected $rowField = 'row_id';
    /**
     * @var
     * customer_entity
     * catalog_product_entity
     */
    protected $vEntityTable;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
        $this->init(false,false,'catalog_product_entity');
    }

    protected function init($bShowAttributeId, $bShowTable, $vEntityTable)
    {
        $this->bShowAttributeId = $bShowAttributeId;
        $this->bShowTable= $bShowTable;
        $this->vEntityTable = $this->resourceConnection->getTableName($vEntityTable);
    }
    protected function inspectFilter($vFieldToFilter)
    {
        $aInspect = $this->inspect();
        $aFilter = array();
        $aMainTable = $aInspect['Main Table'];
        if (!$aMainTable){
            return 'record not found';
        }
        $aEav = $aInspect['Eav'];
        $aAlwaysInclude = array('entity_id','row_id','name','sku');
        foreach ($aMainTable as $k => $v) {
            if ((strpos($k,$vFieldToFilter)!==false) || in_array($k,$aAlwaysInclude)){
                $aFilter['Main Table'][$k] = $v;
            }
        }

        foreach ($aEav as $vStore => $aStoreData) {
            if (!is_array($aStoreData)){
                continue;
            }
            foreach ($aStoreData as $k => $v) {
                //normal
                if (strpos($k,'store_id_')===false){
                    if ((strpos($k,$vFieldToFilter)!==false) || in_array($k,$aAlwaysInclude)){
                        $aFilter['Eav'][$vStore][$k] = $v;
                    }
                }
                //with table information
                else{
                    foreach ($v as $vFieldLoop => $value) {
                        if ((strpos($vFieldLoop,$vFieldToFilter)!==false) || in_array($k,$aAlwaysInclude)){
                            $aFilter['Eav'][$vStore][$k][$vFieldLoop] = $value;
                        }
                    }

                }

            }
        }
        return $aFilter;

    }
    protected function inspect($vFieldsToFilter = '')
    {
        if ($vFieldsToFilter){
            return $this->inspectFilter($vFieldsToFilter);
        }
        $aMain = $this->inspectMain();
        $aEav = $this->inspectEav();
        $aReturn = array(
            'Main Table' => $aMain,
            'Eav' => $aEav,
        );
        return $aReturn;
    }
    protected function getConnection() : \Magento\Framework\DB\Adapter\AdapterInterface
    {
        return $this->resourceConnection->getConnection();
    }
    protected function getRow(string $vSql)
    {
        return  $aReturn = $this->getConnection()->fetchRow($vSql);
    }
    protected function getPair(string $vSql)
    {
        return  $aReturn = $this->getConnection()->fetchPairs($vSql);
    }
    protected function getAllRows(string $vSql)
    {
        return  $aReturn = $this->getConnection()->fetchAll($vSql);
    }
    public function inspectAll($productId, $filter)
    {
        $this->iProductId = $productId;
        if ($filter=='*'){
            return $this->inspect();
        }
        return $this->inspectFilter($filter);
    }
    protected function inspectMain($vAttribute = '*')
    {
        $vTableName = $this->vEntityTable;
        $vSql  = "select $vAttribute from $vTableName WHERE entity_id = {$this->iProductId}";
        $aReturn = $this->getRow($vSql);
        if (!$aReturn){
            throw new \Exception("product_id {$this->iProductId}  not found in the database");
        }

        if (count(array_keys($aReturn)) == 1){
            return current($aReturn);
        }
        //old format does not have row_id
        if (!empty($aReturn['entity_id']) && !isset($aReturn['row_id'])){
            $this->rowField = 'entity_id';
            $this->rowId = $aReturn['entity_id'];
            return $aReturn;
        }
        if (empty($aReturn['row_id'])){
            throw new \Exception('row_id is empty ' . json_encode($aReturn,JSON_PRETTY_PRINT));
        }
        $this->rowId = $aReturn['row_id'];
        return $aReturn;
    }
    protected function inspectEav()
    {
        $aTypeList = array(
            'varchar',
            'int',
            'decimal',
            'text',
            'datetime',
        );
        $aOutput = array();
        foreach ($aTypeList as $vType) {
            $vTable = $this->vEntityTable . "_$vType";
            $aEav =  $this->inspectEavTable($vTable);
            if ($this->bShowTable){
                $aOutput[$vTable] = $aEav;
            }
            else{
                $aOutput= array_merge_recursive($aOutput,$aEav);
            }
        }
        return $aOutput;
    }

    protected function inspectEavTable($vTable)
    {
        $vSql = "SELECT *  FROM $vTable WHERE ({$this->rowField} = '{$this->rowId}')";
        $aAllRows = $this->getAllRows($vSql);
        $aAttributeId = array();
        if (!$aAllRows){
            return ["no match for $vTable"];
        }
        foreach ($aAllRows as $aSingleRow) {
            $aAttributeId[$aSingleRow['attribute_id']] = (int) $aSingleRow['attribute_id'];
        }
        $vAttributeList = implode(',',$aAttributeId);
        if (!$vAttributeList){
            return array();
//            throw new \Exception('No Eav attribute found for ' . $this->iProductId);
        }
        $eavAttributeTable = $this->resourceConnection->getTableName('eav_attribute');
        $vSql = "SELECT attribute_id,attribute_code FROM $eavAttributeTable WHERE attribute_id IN ($vAttributeList)";

        $aAttributeList = $this->getPair($vSql);
        $aEavData = array();
        foreach ($aAllRows as $aSingleRow) {
            if (isset($aAttributeList[ $aSingleRow['attribute_id']])){
                $vAttributeCode = $aAttributeList[ $aSingleRow['attribute_id']];
            }
            else{
                $vAttributeCode = 'unknown-attribute-'  . $aSingleRow['attribute_id'];
            }

            //product etc
            if (isset($aSingleRow['store_id'])){
                $iStoreId = (int) $aSingleRow['store_id'];
                //string key is needed so array_merge merges them properly
                $vStringKey = 'store_id_' . $iStoreId;
                if ($this->bShowAttributeId){
                    $aEavData[$vStringKey ][ $aSingleRow['attribute_id'] . '/' .$vAttributeCode] = $aSingleRow['value'];
                }
                else{
                    $aEavData[$vStringKey ][$vAttributeCode] = $aSingleRow['value'];
                }
            }
            //customer etc
            else{
                if ($this->bShowAttributeId){
                    $aEavData[ $aSingleRow['attribute_id'] . '/' .$vAttributeCode] = $aSingleRow['value'];
                }
                else{
                    $aEavData[$vAttributeCode] = $aSingleRow['value'];
                }
            }
        }

        return $aEavData;

    }
}

;
/** @var \ProductEav $instanceName */
$instanceName = $magentoInc->getObjectFromName('\ProductEav');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}