<?php

$iProductId =982133;
//$iProductId =371029;
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$state = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
/** @var \Magento\Catalog\Model\ProductRepository $product */
$productRepo = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
$product = $productRepo->getById($iProductId);
