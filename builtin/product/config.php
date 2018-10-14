<?php
/**
 * To replace
 *
 * Config
 * $config
 * show_children
 * showChildren
 *
 */
$magentoInc->setAdminHtml();

Class Config
{

    function __construct()
    {

    }

    public function showChildren()
    {
        return 1;
    }
}

;
/** @var \Config $config */
$config = $magentoInc->getObjectFromName('\Config');
$action = $_GET['action'];

if ($action == 'show_children') {
    !d($config->showChildren());
}
else{
    throw new \Exception('no action');
}