<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Cart;

use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote;

/**
 * Class Cart
 *
 * @package Aheadworks\Afptc\Model\Cart
 */
class Cart
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var RuleManagementInterface
     */
    private $ruleManagement;

    /**
     * @param CartRepositoryInterface $cartRepository
     * @param RuleManagementInterface $ruleManagement
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        RuleManagementInterface $ruleManagement
    ) {
        $this->cartRepository = $cartRepository;
        $this->ruleManagement = $ruleManagement;
    }

    /**
     * Add products to cart
     *
     * @param Quote $quote
     * @param CartItemInterface[] $newCartItems
     * @return CartInterface
     */
    public function addProductsToCart($quote, $newCartItems)
    {
        $quoteItems = $quote->getAllItems();
        $quoteItems = array_merge($quoteItems, $newCartItems);
        $quote->setItems($quoteItems);
        $this->cartRepository->save($quote);
        $quote->collectTotals();
        return $quote;
    }

    /**
     * Remove unused promo data from cart
     *
     * @param Quote $quote
     * @return Quote|null
     */
    public function removeUnusedPromoData($quote)
    {
        $updated = false;
        $items = $quote->getAllItems();
        foreach ($items as $item) {
            $afptcQty = (float)$item->getAwAfptcQty();
            $qty = (float)$item->getQty();
            if ($item->getAwAfptcIsPromo()) {
                if ($afptcQty == 0) {
                    $quote->removeItem($item->getId());
                    $updated = true;
                } elseif ($qty > $afptcQty) {
                    $item->setQty($afptcQty);
                    $updated = true;
                }
            }
        }

        $couponCode = $quote->getCouponCode();
        if ($couponCode) {
            $isValidCoupon = $this->ruleManagement->isValidCoupon($couponCode, $quote->getId());
            if ($isValidCoupon === false) {
                $quote
                    ->setCouponCode('')
                    ->setAwAfptcUsesCoupon(null);
                $updated = true;
            }
        }
        return $updated ? $quote : null;
    }
}
