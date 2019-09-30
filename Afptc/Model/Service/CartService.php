<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Service;

use Aheadworks\Afptc\Api\CartManagementInterface;
use Aheadworks\Afptc\Model\Cart\Cart;
use Aheadworks\Afptc\Model\Cart\Converter\ToCartItem;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Aheadworks\Afptc\Model\Cart\Area\Resolver as CartAreaResolver;

/**
 * Class CartService
 *
 * @package Aheadworks\Afptc\Model\Service
 */
class CartService implements CartManagementInterface
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var ToCartItem
     */
    private $converter;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var CartAreaResolver
     */
    private $cartAreaResolver;

    /**
     * @param CartRepositoryInterface $cartRepository
     * @param ToCartItem $converter
     * @param Cart $cart
     * @param CartAreaResolver $cartAreaResolver
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        ToCartItem $converter,
        Cart $cart,
        CartAreaResolver $cartAreaResolver
    ) {
        $this->cartRepository = $cartRepository;
        $this->converter = $converter;
        $this->cart = $cart;
        $this->cartAreaResolver = $cartAreaResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function addPromoProducts($cartId, $metadataRules)
    {
        $newCartItems = $this->converter->convert($cartId, $metadataRules);
        if ($newCartItems) {
            $quote = $this->cartAreaResolver->resolve($cartId);
            $this->cart->addProductsToCart($quote, $newCartItems);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeUnusedPromoData($cart)
    {
        if ($cart instanceof Quote) {
            $quote = $cart;
        } else {
            /** @var Quote $quote */
            $quote = $this->cartRepository->getActive($cart);
        }
        $updatedQuote = $this->cart->removeUnusedPromoData($quote);
        if ($updatedQuote) {
            $this->cartRepository->save($updatedQuote);
        }
    }
}
