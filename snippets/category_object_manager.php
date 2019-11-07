<?php

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$state = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
/** @var \Gibson\Erp\Model\CategoryDisplay  $categoryDisplay */
$categoryDisplay = $objectManager->get('\Gibson\Erp\Model\CategoryDisplay');

/** @var \Magento\Catalog\Model\Category $categoryModel */
$categoryModel = $objectManager->get('\Magento\Catalog\Model\Category');
$categoryModel->load(3);
$categoryDisplay->showThisCategory($categoryModel);

/** @var \Magento\Catalog\Model\ProductRepository $product */
$productRepo = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
$product = $productRepo->getById($iProductId);

