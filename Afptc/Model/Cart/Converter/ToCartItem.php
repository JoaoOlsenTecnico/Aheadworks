<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Cart\Converter;

use Aheadworks\Afptc\Api\Data\CartItemRuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Cart\Converter\ToCartItem\Validator;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\CartItemInterfaceFactory;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty as QtyResolver;
use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory;
use Magento\Quote\Api\Data\CartItemExtensionInterface;
use Aheadworks\Afptc\Api\Data\CartItemRuleInterfaceFactory;

/**
 * Class ToCartItem
 *
 * @package Aheadworks\Afptc\Model\Cart\Converter
 */
class ToCartItem
{
    /**
     * @var CartItemExtensionInterfaceFactory
     */
    private $cartItemExtensionFactory;

    /**
     * @var CartItemRuleInterfaceFactory
     */
    private $cartItemRuleFactory;

    /**
     * @var CartItemInterfaceFactory
     */
    private $cartItemFactory;

    /**
     * @var QtyResolver
     */
    private $qtyResolver;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param CartItemInterfaceFactory $cartItemFactory
     * @param CartItemExtensionInterfaceFactory $cartItemExtensionFactory
     * @param CartItemRuleInterfaceFactory $cartItemRuleFactory
     * @param QtyResolver $qtyResolver
     * @param Validator $validator
     */
    public function __construct(
        CartItemInterfaceFactory $cartItemFactory,
        CartItemExtensionInterfaceFactory $cartItemExtensionFactory,
        CartItemRuleInterfaceFactory $cartItemRuleFactory,
        QtyResolver $qtyResolver,
        Validator $validator
    ) {
        $this->cartItemFactory = $cartItemFactory;
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
        $this->cartItemRuleFactory = $cartItemRuleFactory;
        $this->qtyResolver = $qtyResolver;
        $this->validator = $validator;
    }

    /**
     * Convert rule metadata promo products to cart items
     *
     * @param int $cartId
     * @param RuleMetadataInterface[] $metadataRules
     * @return array
     * @throws \Exception
     */
    public function convert($cartId, $metadataRules)
    {
        $newCartItems = [];
        foreach ($metadataRules as $metadataRule) {
            if (!$this->validator->isValid($metadataRule)) {
                continue;
            }
            foreach ($metadataRule->getPromoProducts() as $promoProduct) {
                $qty = $this->qtyResolver->resolveQtyToDiscountByStock($promoProduct, $metadataRule->getRule());
                if ($qty > 0) {
                    /** @var CartItemInterface $cartItem */
                    $cartItem = $this->cartItemFactory->create();
                    $cartItem
                        ->setSku($promoProduct->getProductSku())
                        ->setQuoteId($cartId)
                        ->setQty($qty);
                    if ($promoProduct->getOption()) {
                        $cartItem->setProductOption($promoProduct->getOption());
                    }

                    /** @var CartItemRuleInterface $cartRuleObject */
                    $cartRuleObject = $this->cartItemRuleFactory->create();
                    $cartRuleObject
                        ->setQty($qty)
                        ->setRuleId($metadataRule->getRule()->getRuleId());

                    $extensionAttributes = $this
                        ->getExtensionAttributes($cartItem)
                        ->setAwUniqueId(uniqid())
                        ->setAwAfptcIsPromo(true)
                        ->setAwAfptcRulesRequest([$cartRuleObject]);
                    $cartItem->setExtensionAttributes($extensionAttributes);

                    $newCartItems[] = $cartItem;
                }
            }
        }
        return $newCartItems;
    }

    /**
     * Retrieve cart item extension interface
     *
     * @param CartItemInterface $item
     * @return CartItemExtensionInterface
     */
    private function getExtensionAttributes($item)
    {
        $extensionAttributes = $item->getExtensionAttributes()
            ? $item->getExtensionAttributes()
            : $this->cartItemExtensionFactory->create();

        return $extensionAttributes;
    }
}
