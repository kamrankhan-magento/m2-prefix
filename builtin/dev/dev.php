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
/** @var \Dev $dev */
$dev = $magentoInc->getObjectFromName('\Dev');
$action = $_GET['action'];

if ($action == 'setup_ignore') {
    !d($dev->setupIgnore());
}
else{
    throw new \Exception('no action');
}