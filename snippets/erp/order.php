<?php
/**
 * To replace
 *
 * ErpOrder
 *
 */


$magentoInc->setAdminHtml();

Class ErpOrder
{

    /**
     * @var \Gibson\Order\Helper\Data
     */
    private $gibsonOrderHelper;
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var \Magento\Sales\Model\Order
     */
    private $order;
    protected $_cachedOrder;
    /**
     * @var \Gibson\Order\Helper\GenerateOrderXml
     */
    private $generateOrderXml;

    function __construct(\Gibson\Order\Helper\Data $gibsonOrderHelper,
                         \Gibson\Order\Helper\GenerateOrderXml $generateOrderXml,
                         \Magento\Sales\Model\Order $order)
    {

        $this->gibsonOrderHelper = $gibsonOrderHelper;
        $this->order = $order;
        $this->generateOrderXml = $generateOrderXml;
    }

    protected function getOrderIncrement()
    {
        //        $increment = '000000701';
        $increment = '000001099';
        return $increment;
    }

    protected function getOrder()
    {
        if (!$this->_cachedOrder) {
            $orderIncrement = $this->getOrderIncrement();
            $this->_cachedOrder = $this->order->loadByIncrementId($orderIncrement);
        }
        return $this->_cachedOrder;
    }

    public function getXml()
    {
        $order = $this->getOrder();
        $gibsonOrder = $this->getGibsonOrderNumber();
        return $this->generateOrderXml->getOrderXml($order,1 , 2, 'dummy order comments', $gibsonOrder);
    }
    public function generateAndWriteOrderXml(){
        $order = $this->getOrder();
        return $this->gibsonOrderHelper->generateOrderXML($order,1,2,"dummy order 
         on multiple lines
         testing 
        comments");
    }
    public function getGibsonOrderNumber()
    {
        $order = $this->getOrder();
        return $this->gibsonOrderHelper->getGibsonOrderNumber($order);
    }
}

;
/** @var \ErpOrder $instanceName */
$instanceName = $magentoInc->getObjectFromName('\ErpOrder');

try {
    !d(ZActionDetect::callMethod($instanceName));
} catch (\ShowExceptionAsNormalMessage $e) {
    $message = $e->errorData ?: $e->getMessage();
    if ($e->rawMessage) {
        echo $e->rawMessage;
    }
    !d($message);
}