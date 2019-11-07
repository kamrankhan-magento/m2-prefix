<?php
/**
 * To replace
 *
 * ErpInvoice
 *
 */
$magentoInc->setAdminHtml();

Class ErpInvoice
{
    /**
     * @var \Gibson\Erp\Model\ImportInvoices
     */
    private $importInvoices;

    public function __construct(\Gibson\Erp\Model\ImportInvoices $importInvoices)
    {
        $this->importInvoices = $importInvoices;
    }
    public function getTableName()
    {
        return $this->importInvoices->getInvoiceTableName();
    }

}

;
/** @var \ErpInvoice $instanceName */
$instanceName = $magentoInc->getObjectFromName('\ErpInvoice');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}