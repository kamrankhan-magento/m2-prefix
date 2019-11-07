<?php
/**
 * To replace
 *
 * AlgoliaTest
 *
 */
$magentoInc->setAdminHtml();

Class AlgoliaTest
{
    private $_client;

    /**
     * @return \AlgoliaSearch\Client
     * @throws Exception
     */
    protected function getClient()
    {
        if (!$this->_client){
            $this->_client = new \AlgoliaSearch\Client('5CM1K9MC75', '9886f17239635f2ac0d13d7800de9612');
        }
        return $this->_client;
    }

    public function viewIndex($indexName )
    {
//        $indexName = 'magento2_default_products_price_default_desc';
        $client = $this->getClient();
        $index = $client->initIndex($indexName);
        /** @var \AlgoliaSearch\IndexBrowser $browse */
        $browse =  $index->browse('');
        return current($browse->answer['hits']);
    }
    public function getSnippetOfAllIndexes()
    {
        $aOutput = [];
        $indexes = $this->listIndex();
        foreach ($indexes as $index) {
          $aOutput[$index] = $this->viewIndex($index);
        }
        return $aOutput;

    }
    public function listIndex()
    {
        $client = $this->getClient();
        return array_column($client->listIndexes()['items'],'name');

    }

}

;
/** @var \AlgoliaTest $instanceName */
$instanceName = $magentoInc->getObjectFromName('\AlgoliaTest');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}