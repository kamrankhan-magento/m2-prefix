<?php
/**
 * To replace
 *
 * ManageTheme
 *
 */
$magentoInc->setAdminHtml();

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\DesignInterface;
use Magento\Store\Model\Store;


Class ManageTheme
{

    /**
     * @var \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var \Magento\Theme\Model\Config
     */
    private $config;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    public function __construct(\Magento\Theme\Model\ResourceModel\Theme\CollectionFactory $collectionFactory,
                                \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
                                \Magento\Framework\App\ResourceConnection $resourceConnection,
                                \Magento\Theme\Model\Config $config)
    {
        $this->collectionFactory = $collectionFactory;
        $this->config = $config;
        $this->scopeConfig = $scopeConfig;
        $this->resourceConnection = $resourceConnection;
    }

    protected function assignTheme(string $themeCode, $storeId)
    {
        $themes = $this->collectionFactory->create()->loadRegisteredThemes();
        /**
         * @var \Magento\Theme\Model\Theme $theme
         */
        $aThemeCodes = [];
        foreach ($themes as $theme) {
            $aThemeCodes[] = $theme->getCode();
            if ($theme->getCode() == $themeCode) {
                $this->config->assignToStore(
                    $theme,
                    [$storeId],
//                    ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                    'stores'
                );
                return "Theme set as $themeCode";
            }
        }
        return [
            'message'      => "Theme $themeCode not found in activeThemes",
            'activeThemes' => $aThemeCodes,
        ];
    }

    public function setLumaTheme()
    {
        return $this->assignTheme('Magento/luma', 1);
    }

    public function setTheme(string $themeCode, $storeId)
    {
        return $this->assignTheme($themeCode, $storeId);
    }
    public function getThemeConfigFromDb()
    {
        $path = DesignInterface::XML_PATH_THEME_ID;
        $sql = "select * from core_config_data where path = '$path'";
        return $this->resourceConnection->getConnection()->fetchAssoc($sql);
    }
}

;
/** @var \ManageTheme $instanceName */
$instanceName = $magentoInc->getObjectFromName('\ManageTheme');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData ?: $e->getMessage();
    if ($e->rawMessage) {
        echo $e->rawMessage;
    }
    !d($message);
}