<?php
/**
 * To replace
 *
 * CatalogProduct
 *
 */
$magentoInc->setAdminHtml();

Class CatalogProduct
{

    /**
     * @var \Gibson\Theme\Model\Urls
     */
    private $urls;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    function __construct(\Gibson\Theme\Model\Urls $urls,
        Magento\Catalog\Api\ProductRepositoryInterface $productRepository)
    {

        $this->urls = $urls;
        $this->productRepository = $productRepository;
    }
    protected function getProduct()
    {
        $productId = 372774;
        return $this->productRepository->getById($productId);
    }
    public function getUrl()
    {
        $product = $this->getProduct();
        return $this->urls->getProductUrl($product);
    }
}

;
/** @var \CatalogProduct $instanceName */
$instanceName = $magentoInc->getObjectFromName('\CatalogProduct');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}