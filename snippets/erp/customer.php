<?php
$initialClasses = get_declared_classes();

$magentoInc->setAdminHtml();

Class ErpCustomer
{

    /**
     * @var \Gibson\Erp\Cron\GibsonFullSync
     */
    private $gibsonFullSync;

    function __construct(\Gibson\Erp\Cron\GibsonFullSync $gibsonFullSync)
    {

        $this->gibsonFullSync = $gibsonFullSync;
    }

    public function syncWithDelete()
    {
        return $this->gibsonFullSync->importCustomers();
//        return $this->gibsonFullSync->importCustomers(true);
    }
}


ZActionDetect::showOutput(end($initialClasses),$magentoInc);
