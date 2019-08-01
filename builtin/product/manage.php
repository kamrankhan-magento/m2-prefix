<?php
/**
 * To replace
 *
 * ManageProduct
 *
 */
$magentoInc->setAdminHtml();

Class ManageProduct
{

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    function __construct(\Magento\Catalog\Api\ProductRepositoryInterface $productRepository)
    {

        $this->productRepository = $productRepository;
    }

    public function deleteBySku($sku)
    {
        return $this->productRepository->deleteById($sku);
    }
}

;
/** @var \ManageProduct $instanceName */
$instanceName = $magentoInc->getObjectFromName('\ManageProduct');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}