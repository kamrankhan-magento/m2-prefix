<?php
/**
 * To replace
 *
 * ErpProduct
 *
 */
$magentoInc->setAdminHtml();

Class ErpProduct
{

    /**
     * @var \Gibson\Erp\Model\CreateProducts
     */
    private $createProducts;
    /**
     * @var \Gibson\Erp\Model\ImportProductCSV
     */
    private $importProductCSV;
    /**
     * @var \Symfony\Component\Console\Input\ArrayInputFactory
     */
    private $arrayInputFactory;
    /**
     * @var \Symfony\Component\Console\Output\NullOutputFactory
     */
    private $outputFactory;
    /**
     * @var \Gibson\Erp\Console\Command\DeleteProductsFactory
     */
    private $deleteProductsFactory;
    /**
     * @var \Gibson\Erp\Model\Incremental\ProductImport
     */
    private $productImport;

    function __construct(\Gibson\Erp\Model\CreateProducts $createProducts,
                         \Symfony\Component\Console\Input\ArrayInputFactory $arrayInputFactory,
                         \Symfony\Component\Console\Output\NullOutputFactory $outputFactory,
                         \Gibson\Erp\Console\Command\DeleteProductsFactory $deleteProductsFactory,
                         \Gibson\Erp\Model\Incremental\ProductImport $productImport,
                         Gibson\Erp\Model\ImportProductCSV $importProductCSV)
    {

        $this->createProducts = $createProducts;
        $this->importProductCSV = $importProductCSV;
        $this->arrayInputFactory = $arrayInputFactory;
        $this->outputFactory = $outputFactory;
        $this->deleteProductsFactory = $deleteProductsFactory;
        $this->productImport = $productImport;
    }

    public function importCsv()
    {
        return $this->createProducts->importFromCsvFile();
    }
    public function getProductCsv()
    {
        return $this->importProductCSV->getProductsCsv();
    }
    public function deleteProducts()
    {
        /** @var \Gibson\Erp\Console\Command\DeleteProducts $deleteProducts */
        $deleteProducts = $this->deleteProductsFactory->create();
        /** @var Symfony\Component\Console\Input\ArrayInput $input */
        $input = $this->arrayInputFactory->create(['parameters'=>['one'=>1]]);
        /** @var \Symfony\Component\Console\Output\NullOutput $output */
        $output = $this->outputFactory->create();
        return $deleteProducts->execute($input,$output);
    }
    public function incrementalImport()
    {
        return $this->productImport->importMissingProducts(4);
    }
}

;
/** @var \ErpProduct $instanceName */
$instanceName = $magentoInc->getObjectFromName('\ErpProduct');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}