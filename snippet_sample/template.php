<?php
$initialClasses = get_declared_classes();

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

if (empty($GLOBALS['just_include_snippet_class'])) {

    $instanceName = $magentoInc->getObjectFromName(end($initialClasses));

    try {
        !d(ZActionDetect::callMethod($instanceName));
    } catch (\ShowExceptionAsNormalMessage $e) {
        $message = $e->errorData ?: $e->getMessage();
        if ($e->rawMessage) {
            echo $e->rawMessage;
        }
        !d($message);
    }
}