<?php
/**
 * To replace
 *
 * OrderApi
 *
 */
$magentoInc->setRestApiArea();

Class OrderApi
{

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    function __construct(\Magento\Sales\Api\OrderRepositoryInterface $orderRepository)
    {

        $this->orderRepository = $orderRepository;
    }

    public function showOrder(int $orderId)
    {
        $order = $this->orderRepository->get($orderId);
        $orderView = \ZOrderView::getOrder($order);
        return $orderView;
    }
}

;
/** @var \OrderApi $instanceName */
$instanceName = $magentoInc->getObjectFromName('\OrderApi');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData?:$e->getMessage();
    if ($e->rawMessage){
        echo $e->rawMessage;
    }
    !d($message);
}