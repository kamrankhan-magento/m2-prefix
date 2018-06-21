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
    /**
     * @var \Magento\GiftCardAccount\Helper\Data
     */
    private $helperGiftCard;
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;
    /**
     * @var \Magento\GiftCardAccount\Model\GiftcardaccountFactory
     */
    private $fAccountGiftCard;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Quote\Model\QuoteFactory $quote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\GiftCardAccount\Model\GiftcardaccountFactory $fAccountGiftCard,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\GiftCardAccount\Helper\Data $helperGiftCard,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
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
        $this->helperGiftCard = $helperGiftCard;
        $this->priceCurrency = $priceCurrency;
        $this->fAccountGiftCard = $fAccountGiftCard;
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
//        $store = $this->storeManager->getStore();
        $websiteId = $orderData['website_id'];
        $website = $this->storeManager->getWebsite($websiteId);
        $oDefaultStore = $website->getDefaultStore();
        $this->storeManager->setCurrentStore($oDefaultStore->getCode());
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($orderData['email']);// load customet by email address
        if (!$customer->getEntityId()) {
            //If not avilable then create this customer
            $customer->setWebsiteId($websiteId)
                ->setStore($oDefaultStore)
                ->setFirstname($orderData['shipping_address']['firstname'])
                ->setLastname($orderData['shipping_address']['lastname'])
                ->setEmail($orderData['email'])
                ->setPassword($orderData['email']);
            $customer->save();
        }
        $quote = $this->quote->create(); //Create object of quote
        $quote->setStore($oDefaultStore); //set store for which you create quote
        // if you have allready buyer id then you can load customer directly
        $customer = $this->customerRepository->getById($customer->getEntityId());
        $quote->setCurrency();
        $quote->setData('authority_to_leave',1);
        $quote->assignCustomer($customer); //Assign quote to customer

        //add items in quote
        foreach ($orderData['items'] as $item) {
            $product = $this->product->load($item['product_id']);
            if (isset($item['price'])) {
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
        $vShippingMethodCode = $orderData['shipping_method'];

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($vShippingMethodCode); //shipping method
        $quote->setInventoryProcessed(false); //not effetc inventory
        $quote->setWebsite($website);
        $quote->setStoreId($oDefaultStore->getId());
        $quote->save(); //Now Save quote and your quote is ready

        if (!empty($orderData['giftCardCode'])) {
            $this->applyGiftCardFromCode($orderData['giftCardCode'], $quote);
        }
        // Set Sales Order Payment
        $quote->getPayment()->importData(
            $orderData['payment']
        );

        // Collect Totals & Save Quote
        $quote->collectTotals()->save();


        // Create Order From Quote
        $order = $this->quoteManagement->submit($quote);

        $order->setEmailSent(0);
        if ($order->getEntityId()) {
            $result['order_id'] = $order->getRealOrderId();
            $result['increment_id'] = $order->getIncrementId();
            $result['data'] = $this->cleanArray($order->getData());
        }
        else {
            $result = ['error' => 1, 'msg' => 'Your custom message'];
        }
        return $result;
    }

    public function cleanArray(array $aData)
    {
        return array_map(function ($element) {
            if (is_object($element)){
                if (is_callable([$element,'getData'])){
                    $element = $element->getData();
                }
            }
            return is_object($element) ? @json_decode(@json_encode($element),true) : (is_array($element) ? $this->cleanArray($element) : $element);
        }, $aData);
    }


    protected function sampleOrderData()
    {
        return [
            'currency_id'      => 'USD',
            'email'            => 'test@webkul.com', //buyer email id
            //select distinct code from quote_shipping_rate;
            //        'shipping_method'  => 'flatrate_flatrate',
            //        'shipping_method'  => 'standard',
            'shipping_method'  => 'freeshipping_freeshipping',
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
            ],
            'website_id'       => 1,
            //            'payment'=>['method' => 'checkmo'],
            'payment'          => ['method' => 'pinpay'],
            'giftCardCode'     => 'GIFTCARD-1',
        ];
    }

    protected function applyGiftCard(\Magento\GiftCardAccount\Model\Giftcardaccount $model,
                                     \Magento\Quote\Model\Quote $quote)
    {
        return $model->addToCart(true, $quote);
    }

    protected function applyGiftCardFromCode($vCode, \Magento\Quote\Model\Quote $quote)
    {
        $giftCard = $this->fAccountGiftCard->create()->loadByCode($vCode);
        return $this->applyGiftCard($giftCard, $quote);
    }
}