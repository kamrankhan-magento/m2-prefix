<?php
$initialClasses = get_declared_classes();

$magentoInc->setAdminHtml();

class ClassName
{
    public function __construct()
    {
    }

    public function test()
    {
        return 1;
    }
}

ZActionDetect::showOutput(end($initialClasses), $magentoInc);
