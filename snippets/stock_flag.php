<?php
/**
 * To replace
 *
 * StockFlag
 *
 */
$magentoInc->setAdminHtml();

Class StockFlag
{
    private $productRepository;
    private $gibsonHelper;

    function __construct(\Magento\Catalog\Model\ProductRepository $productRepository,
                         \Gibson\Theme\Helper\Data $gibsonHelper)
    {
        $this->productRepository = $productRepository;
        $this->gibsonHelper = $gibsonHelper;
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
    public function main()
    {
        return 1;
    }
}

;
/** @var \StockFlag $instanceName */
$instanceName = $magentoInc->getObjectFromName('\StockFlag');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}