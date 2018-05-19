<?php

Class ZCreateOrder
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product;
    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    private $formkey;
    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    private $quote;
    /**
     * @var \Magento\Quote\Model\QuoteManagement
     */
    private $quoteManagement;
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $customerFactory;
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var \Magento\Sales\Model\Service\OrderService
     */
    private $orderService;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Quote\Model\QuoteFactory $quote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Service\OrderService $orderService
    )
    {
        $this->storeManager = $storeManager;
        $this->product = $product;
        $this->formkey = $formKey;
        $this->quote = $quote;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderService = $orderService;
    }

    /**
     * Create Order On Your Store
     *
     * @param array $orderData
     *
     * @return array
     *
     */
    public function createMageOrder($orderData)
    {
        $store = $this->storeManager->getStore();
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($orderData['email']);// load customet by email address
        if (!$customer->getEntityId()) {
            //If not avilable then create this customer
            $customer->setWebsiteId($websiteId)
                ->setStore($store)
                ->setFirstname($orderData['shipping_address']['firstname'])
                ->setLastname($orderData['shipping_address']['lastname'])
                ->setEmail($orderData['email'])
                ->setPassword($orderData['email']);
            $customer->save();
        }
        $quote = $this->quote->create(); //Create object of quote
        $quote->setStore($store); //set store for which you create quote
        // if you have allready buyer id then you can load customer directly
        $customer = $this->customerRepository->getById($customer->getEntityId());
        $quote->setCurrency();
        $quote->assignCustomer($customer); //Assign quote to customer

        //add items in quote
        foreach ($orderData['items'] as $item) {
            $product = $this->product->load($item['product_id']);
            if (isset($item['price'])){
                $product->setPrice($item['price']);
            }
            $quote->addProduct(
                $product,
                intval($item['qty'])
            );
        }

        //Set Address to quote
        $quote->getBillingAddress()->addData($orderData['shipping_address']);
        $quote->getShippingAddress()->addData($orderData['shipping_address']);

        // Collect Rates and Set Shipping & Payment Method
        $vShippingMethodCode= 'flatrate_flatrate';
//        $vShippingMethodCode= 'freeshipping_freeshipping';

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($vShippingMethodCode); //shipping method
        $quote->setPaymentMethod('checkmo'); //payment method
        $quote->setInventoryProcessed(false); //not effetc inventory
        $quote->save(); //Now Save quote and your quote is ready

        // Set Sales Order Payment
        $quote->getPayment()->importData(['method' => 'checkmo']);

        // Collect Totals & Save Quote
        $quote->collectTotals()->save();


        // Create Order From Quote
        $order = $this->quoteManagement->submit($quote);

        $order->setEmailSent(0);
        if ($order->getEntityId()) {
            $result['order_id'] = $order->getRealOrderId();
            $result['increment_id'] = $order->getIncrementId();
            $result['data'] = $order->getData();
        }
        else {
            $result = ['error' => 1, 'msg' => 'Your custom message'];
        }
        return $result;
    }

    protected function sampleOrderData()
    {
        return [
            'currency_id'      => 'USD',
            'email'            => 'test@webkul.com', //buyer email id
            'shipping_address' => [
                'firstname'            => 'jhon', //address Details
                'lastname'             => 'Deo',
                'street'               => 'xxxxx',
                'city'                 => 'xxxxx',
                'country_id'           => 'IN',
                'region'               => 'xxx',
                'postcode'             => '43244',
                'telephone'            => '52332',
                'fax'                  => '32423',
                'save_in_address_book' => 1
            ],
            'items'            => [ //array of product which order you want to create
                                    ['product_id' => '1', 'qty' => 1],
                                    ['product_id' => '2', 'qty' => 2]
            ]
        ];
    }
}