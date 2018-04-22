<?php
function setStateAdminHtml()
{
    global $app;
    static $bSet = false;
    if ($bSet){
        return ;
    }
    /** @var \Magento\Framework\App\State $state */
    $state = $app->getObjectManager()->get('\Magento\Framework\App\State');
    $state->setAreaCode('adminhtml');
    $bSet = true;
}

function getObjectFromName($vClass)
{
    global $app;
    return $app->getObjectManager()->create($vClass);
}