<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Observer;

use Aheadworks\Afptc\Api\CartManagementInterface;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Quote\Model\Quote\Item;
use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Class AddPromoProductToCart
 *
 * @package Aheadworks\Afptc\Observer
 */
class AddPromoProductToCart implements ObserverInterface
{
    /**
     * @var CartManagementInterface
     */
    private $cartManagement;

    /**
     * @var RuleManagementInterface
     */
    private $ruleManagement;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @param CartManagementInterface $cartManagement
     * @param CheckoutSession $checkoutSession
     * @param RuleManagementInterface $ruleManagement
     */
    public function __construct(
        CartManagementInterface $cartManagement,
        CheckoutSession $checkoutSession,
        RuleManagementInterface $ruleManagement
    ) {
        $this->cartManagement = $cartManagement;
        $this->checkoutSession = $checkoutSession;
        $this->ruleManagement = $ruleManagement;
    }

    /**
     * Add promo products to cart, if the rules for the newly added product are met
     *
     * @param Observer $observer
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        $quote = $this->checkoutSession->getQuote();
        /** @var Product $addedProduct */
        $addedProduct = $observer->getEvent()->getProduct();
        /** @var Item $quoteItem */
        $lastQuoteItem = $quote->getItemByProduct($addedProduct);

        if ($lastQuoteItem) {
            $metadataRules = $this->ruleManagement->getAutoAddMetadataRules(
                $quote->getId(),
                $lastQuoteItem->getItemId()
            );
            $this->cartManagement->addPromoProducts($quote->getId(), $metadataRules);
        }

        return $this;
    }
}
