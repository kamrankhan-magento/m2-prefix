<?php
/**
 * To replace
 *
 * ClassName
 *
 */
$magentoInc->setAdminHtml();

Class ClassName
{

    function __construct()
    {

    }

    public function main()
    {
        return 1;
    }
}

;
/** @var \ClassName $instanceName */
$instanceName = $magentoInc->getObjectFromName('\ClassName');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}