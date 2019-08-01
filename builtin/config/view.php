<?php
/**
 * To replace
 *
 * ConfigView
 *
 */
$magentoInc->setAdminHtml();

Class ConfigView
{

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection)
    {

        $this->resourceConnection = $resourceConnection;
    }
    protected function getConnection() : \Magento\Framework\DB\Adapter\AdapterInterface
    {
        return $this->resourceConnection->getConnection();
    }
    protected function getAllRows(string $vSql)
    {
        return  $aReturn = $this->getConnection()->fetchAssoc($vSql);
    }

    public function getConfig(string $path)
    {
        return $this->getAllRows("select * from core_config_data where path like '%$path%'");
    }
}

;
/** @var \ConfigView $instanceName */
$instanceName = $magentoInc->getObjectFromName('\ConfigView');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}