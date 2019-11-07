<?php
/**
 * To replace
 *
 * StockFlag
 *
 */
$magentoInc->setAdminHtml();

Class Category
{
    private $productRepository;
    private $gibsonHelper;
    /**
     * @var \Gibson\Erp\Model\CategoryDisplay
     */
    private $categoryDisplay;
    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    private $directoryList;
    /**
     * @var \Gibson\Erp\Model\ImportCategoryCSV
     */
    private $importCategoryCSV;
    /**
     * @var \Gibson\Erp\Model\CsvImportCategory
     */
    private $csvImportCategory;
    /**
     * @var \Gibson\Erp\Cron\GibsonFullSync
     */
    private $gibsonFullSync;

    function __construct(\Magento\Catalog\Model\ProductRepository $productRepository,
                         \Gibson\Erp\Model\CategoryDisplay $categoryDisplay,
                         \Magento\Framework\Filesystem\DirectoryList $directoryList,
                         \Gibson\Erp\Model\ImportCategoryCSV $importCategoryCSV,
                         \Gibson\Erp\Cron\GibsonFullSync $gibsonFullSync,
                         \Gibson\Erp\Model\CsvImportCategory $csvImportCategory,
                         \Gibson\Theme\Helper\Data $gibsonHelper)
    {
        $this->productRepository = $productRepository;
        $this->gibsonHelper = $gibsonHelper;
        $this->categoryDisplay = $categoryDisplay;
        $this->directoryList = $directoryList;
        $this->importCategoryCSV = $importCategoryCSV;
        $this->csvImportCategory = $csvImportCategory;
        $this->gibsonFullSync = $gibsonFullSync;
    }
    protected function getProductId()
    {
        return 982133;
    }

    protected function getProduct() : \Magento\Catalog\Api\Data\ProductInterface
    {
        return $this->productRepository->getById($this->getProductId());
    }
    public function canShowInStock()
    {
        $product = $this->getProduct();
        $stock = $this->gibsonHelper->getProductStockObject($product);

        return $this->gibsonHelper->canShowInStock($stock,$product);
    }
    public function generateMenuHtml()
    {
        return $this->categoryDisplay->generateMenuHtml();
    }
    public function getStaticBlock()
    {
        return $this->categoryDisplay->getMenuFromStaticBlock();
    }
    public function getCachedHtml()
    {
        return $this->categoryDisplay->getCachedMenuHtml();
    }
    public function getMenuHtml()
    {
        return $this->categoryDisplay->getMenuHtml();
    }
    public function getCategories()
    {
        $categories = $this->categoryDisplay->getMainCategories();
        return \Gibson\Erp\Model\Helper\ArrayHelper::getVarienDataListTemp($categories,true);
    }
    public function syncCategories()
    {
        //nothing in these classes is used
//        $root =  $this->directoryList->getRoot();
//
//        $gibsonErpCategory = dirname($root) . '/gibson-erp/class-category.php';
//        $db = dirname($root) . '/gibson-erp/class.db.php';
//        require_once $db;
//        require_once $gibsonErpCategory;
//        /**
//         * @see \Gibson_Category::__construct()
//         * saves category to ERP as soon as class constructor is called
//         */
//        $category = new Gibson_Category();
        $this->gibsonFullSync->deleteCategories();

//        $this->importCategoryCSV->generateCsv();
//        return $this->csvImportCategory->importFromCsvFile();
         $this->gibsonFullSync->importCategories();
         return ['note'=>'might need indexing to display categories, keep it in mind'];
    }
    public function getBrandCategories()
    {
        $categories = $this->categoryDisplay->getBrandCategories();
        return \Gibson\Erp\Model\Helper\ArrayHelper::getVarienDataListTemp($categories,true);
    }
    public function emptyMenuContents()
    {
        return $this->categoryDisplay->emptyMenuForTest();
    }


}

//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//$category = $objectManager->get('\Category');
//return;

/** @var \Category $instanceName */
$instanceName = $magentoInc->getObjectFromName('\Category');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}