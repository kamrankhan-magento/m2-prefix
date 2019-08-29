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


ZActionDetect::showOutput(end($initialClasses),$magentoInc);
