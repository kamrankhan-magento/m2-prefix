<?php

use Magento\Framework\App\Area;

Class MagentoInc
{
    /**
     * @var \Magento\Framework\App\State
     */
    private $state;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    protected $bStateSet = false;
    /**
     * @var \Magento\Store\Model\StoreManager
     */
    private $storeManager;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Magento\Catalog\Model\Indexer\Product\Flat\State
     */
    private $flatState;
    /**
     * @var \Magento\Framework\Indexer\IndexerRegistry
     */
    private $indexerRegistry;

    function __construct(\Magento\Framework\App\State $state,
                         \Magento\Store\Model\StoreManager $storeManager,
                         \Magento\Framework\Registry $registry,
                         \Magento\Catalog\Model\Indexer\Product\Flat\State $flatState,
                         \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
                         \Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->state = $state;
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        $this->registry = $registry;
        $this->flatState = $flatState;
        $this->indexerRegistry = $indexerRegistry;
    }

    public function setAdminHtml()
    {
        if ($this->bStateSet) {
            return false;
        }
        $this->state->setAreaCode('adminhtml');
        $this->storeManager->setCurrentStore('admin');
        $this->registry->register('isSecureArea', true);
        $this->bStateSet = true;
    }
    //don't call it in boot time, call it just before
    public function setFrontEndStore(string $storeCode = 'default')
    {
        if ($this->bStateSet) {
            return false;
        }
        $this->storeManager->setCurrentStore($storeCode);
        $this->bStateSet = true;
    }
    public function setState($area = Area::AREA_FRONTEND)
    {
        $this->state->setAreaCode($area);
    }

    public function setRestApiArea()
    {
        if ($this->bStateSet) {
            return false;
        }
        $this->state->setAreaCode('webapi_rest');
        $this->storeManager->setCurrentStore('admin');
        $this->registry->register('isSecureArea', true);
        $this->bStateSet = true;
    }

    public function notUseFlat()
    {
        /**
         * @see \Magento\Catalog\Model\ResourceModel\Product\Collection::isEnabledFlat()
         * @see \Magento\Catalog\Model\Indexer\AbstractFlatState::isAvailable()
         */
        /** @var \Magento\Indexer\Model\Indexer\DependencyDecorator $productFlatIndexer */
        $productFlatIndexer = $this->indexerRegistry->get($this->flatState::INDEXER_ID);
        $productFlatIndexer->invalidate();
    }

    public function getObjectFromName($vClass)
    {
        return $this->objectManager->get($vClass);
    }

    public static function largeCacheResponse(Closure $executeToCache, $vCacheId,$bResetCache = false)
    {
        $vPath = dirname(__DIR__) . "/snippets/json/$vCacheId.json";
        $aDecoded = null;
        if (!$bResetCache &&  file_exists($vPath)) {
            $aDecoded = json_decode(file_get_contents($vPath),true);
        }
        else{
            $vDir = dirname($vPath);
            if (!file_exists($vDir)) {
                mkdir($vDir, 0777, true);
            }
            touch($vPath);
        }
        if (!is_array($aDecoded)){
            $aDecoded = $executeToCache();
            file_put_contents($vPath, json_encode($aDecoded,JSON_PRETTY_PRINT));
        }
        return $aDecoded;
    }
}

;

if (!isset($app)) {
    echo "<pre>";
    debug_print_backtrace();
    echo "</pre>";
    var_dump('not set app');
    die;
}
/** @var \MagentoInc $magentoInc */
$magentoInc = $app->getObjectManager()->create('\MagentoInc');
$GLOBALS['magentoInc'] = $magentoInc;
function setStateAdminHtml()
{
    global $magentoInc;
    $magentoInc->setAdminHtml();
}

function getObjectFromName($vClass)
{
    global $magentoInc;
    return $magentoInc->getObjectFromName($vClass);
}
require_once __DIR__ . '/ZCreateOrder.php';
require_once __DIR__ . '/ZOrderView.php';
require_once __DIR__ . '/ZCreateGiftCard.php';
require_once __DIR__ . '/ZGeneric.php';
require_once __DIR__ . '/action_detect.php';