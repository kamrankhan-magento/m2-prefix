<?php

setStateAdminHtml();

Class baseUrl{
    function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        d($scopeConfig->getValue('web/unsecure/base_url'));
    }
};
getObjectFromName('\baseUrl');

/**
 * Alternative approach without DI
 */

/** @var \Magento\Framework\App\Config\ScopeConfigInterface $configAnotherWay */
$configAnotherWay = getObjectFromName('\Magento\Framework\App\Config\ScopeConfigInterface');
d($configAnotherWay->getValue('web/unsecure/base_url'));