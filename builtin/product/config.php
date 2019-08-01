<?php
/**
 * To replace
 *
 * Config
 * $config
 * show_children
 * showChildren
 *
 */
$magentoInc->setAdminHtml();

Class Config
{

    /**
     * @var \Magento\ConfigurableProduct\Api\LinkManagementInterface
     */
    private $linkManagement;

    function __construct(\Magento\ConfigurableProduct\Api\LinkManagementInterface $linkManagement)
    {

        $this->linkManagement = $linkManagement;
    }

    public function showChildren()
    {
        $sku = $_GET['sku'];;
        $childProducts =  $this->linkManagement->getChildren($sku);
        $return = [];
        foreach ($childProducts as $product) {
            $return[] = $product->getId();
        }
        return implode(', ',$return);
    }
}

;
/** @var \Config $config */
$config = $magentoInc->getObjectFromName('\Config');
$action = $_GET['action'];

if ($action == 'show_children') {
    !d($config->showChildren());
}
else{
    throw new \Exception('no action');
}