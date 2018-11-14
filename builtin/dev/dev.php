<?php
/**
 * To replace
 *
 * Dev
 * $dev
 * setup_ignore
 * setupIgnore
 *
 */
$magentoInc->setAdminHtml();

Class Dev
{

    /**
     * @var \Magento\Framework\Config\CacheInterface
     */
    private $cache;

    function __construct(\Magento\Framework\Config\CacheInterface $cache)
    {

        $this->cache = $cache;
    }

    public function setupIgnore()
    {
        return $this->cache->save(serialize(true),'db_is_up_to_date');
    }
}

;
/** @var \Dev $instanceName */
$instanceName = $magentoInc->getObjectFromName('\Dev');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}