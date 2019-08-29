<?php

$iProductId =982133;
//$iProductId =371029;
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$state = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
/** @var \Magento\Catalog\Model\ProductRepository $product */
$productRepo = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
$product = $productRepo->getById($iProductId);



/** @var \Magento\Framework\Filesystem\DirectoryList $directoryList */
$directoryList = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
$varPath = $directoryList->getPath('var');
$deployRoot = dirname(dirname(dirname(dirname($varPath))));
$sharedStockistPath = realpath($deployRoot . '/shared/public/pub/stockist/stockists_brands.txt');