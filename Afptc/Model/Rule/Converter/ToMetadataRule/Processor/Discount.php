<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty as QtyResolver;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Metadata\Rule\PromoProductFactory;

/**
 * Class Discount
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor
 */
class Discount implements ProcessorInterface
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var QtyResolver
     */
    private $qtyResolver;

    /**
     * @var PromoProductFactory
     */
    private $promoProductFactory;

    /**
     * @param Validator $validator
     * @param QtyResolver $qtyResolver
     * @param PromoProductFactory $promoProductFactory
     */
    public function __construct(
        Validator $validator,
        QtyResolver $qtyResolver,
        PromoProductFactory $promoProductFactory
    ) {
        $this->validator = $validator;
        $this->qtyResolver = $qtyResolver;
        $this->promoProductFactory = $promoProductFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareData($ruleMetadata, $items, $metadataRules)
    {
        $totalQty = $this->qtyResolver->resolveQtyToDiscountByRule($ruleMetadata->getRule(), $items);
        if ($totalQty) {
            $this->attachPromoProducts($ruleMetadata, $items, $metadataRules, $totalQty);
        }
    }

    /**
     * Attach promo products to discount
     *
     * @param RuleMetadataInterface $ruleMetadata
     * @param AbstractItem[] $items
     * @param RuleMetadataInterface[]
     * @param float $totalQty
     * @return void
     */
    private function attachPromoProducts($ruleMetadata, $items, $metadataRules, $totalQty)
    {
        $qtyInDiscount = 0;
        $promoProducts = [];
        $rule = $ruleMetadata->getRule();
        $availableQtyToGive = $this->qtyResolver->resolveQtyToGive($rule, $totalQty);
        foreach ($items as $item) {
            // Actions validator
            if ($item->getAwAfptcIsPromo()
                && $qtyInDiscount < $availableQtyToGive
                && $this->validator->isValidByPromoProducts($item, $rule->getPromoProducts())
            ) {
                $qtyToGiveLeft = $availableQtyToGive - $qtyInDiscount;
                $qtyToDiscount = $this->qtyResolver
                    ->resolveQtyItemByMetadataRules($metadataRules, $rule, $item, $qtyToGiveLeft);
                if ($qtyToDiscount > 0) {
                    $itemId = $item->getAwAfptcId();
                    $promoProduct = $this->promoProductFactory->create(
                        [
                            RuleMetadataPromoProductInterface::UNIQUE_KEY => $itemId,
                            RuleMetadataPromoProductInterface::PRODUCT_SKU => $item->getProduct()->getData('sku'),
                            RuleMetadataPromoProductInterface::QTY => $qtyToDiscount
                        ]
                    );
                    $promoProducts[$itemId] = $promoProduct;
                    $qtyInDiscount += $qtyToDiscount;
                }
            }
        }
        $ruleMetadata->setPromoProducts($promoProducts);
    }
}
