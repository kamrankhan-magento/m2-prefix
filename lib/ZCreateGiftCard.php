<?php
Class ZCreateGiftCard
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
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $repositoryProduct;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $fProduct;
    /**
     * @var \Magento\GiftCardAccount\Model\GiftcardaccountFactory
     */
    private $fAccountGiftCard;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product $product,
        \Magento\Catalog\Model\ProductFactory $fProduct,
        \Magento\GiftCardAccount\Model\GiftcardaccountFactory $fAccountGiftCard,
        \Magento\Catalog\Model\ProductRepository $repositoryProduct
    )
    {
        $this->storeManager = $storeManager;
        $this->product = $product;
        $this->repositoryProduct = $repositoryProduct;
        $this->fProduct = $fProduct;
        $this->fAccountGiftCard = $fAccountGiftCard;
    }


    public function createGiftCard($vCode,$fAmount,$fAmountSpent,$iWebsiteId) : \Magento\GiftCardAccount\Model\Giftcardaccount
    {
        $giftCardAccount = $this->fAccountGiftCard->create();
        $giftCardAccount = $giftCardAccount->loadByCode($vCode);
        if ($giftCardAccount->isObjectNew()){
            $giftCardAccount = $this->fAccountGiftCard->create();
        }
        $giftCardAccount->setWebsiteId($iWebsiteId);
        $giftCardAccount->setCode($vCode);
        $giftCardAccount->setGiftCardsAmount($fAmount);
        $giftCardAccount->setGiftCardsAmountUsed($fAmountSpent);
        $giftCardAccount->setBalance($fAmount-$fAmountSpent);
        $giftCardAccount->setStatus(\Magento\GiftCardAccount\Model\Giftcardaccount::STATUS_ENABLED);
        $giftCardAccount->save();
        return $giftCardAccount;
    }
}