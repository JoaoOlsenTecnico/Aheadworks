<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;
use Aheadworks\Afptc\Model\Metadata\Rule\PromoProductFactory;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty as QtyResolver;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Option as OptionResolver;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Popup
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor
 */
class Popup implements ProcessorInterface
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
     * @var OptionResolver
     */
    private $optionResolver;

    /**
     * @var PromoProductFactory
     */
    private $promoProductFactory;

    /**
     * @param Validator $validator
     * @param QtyResolver $qtyResolver
     * @param OptionResolver $optionResolver
     * @param PromoProductFactory $promoProductFactory
     */
    public function __construct(
        Validator $validator,
        QtyResolver $qtyResolver,
        OptionResolver $optionResolver,
        PromoProductFactory $promoProductFactory
    ) {
        $this->validator = $validator;
        $this->qtyResolver = $qtyResolver;
        $this->optionResolver = $optionResolver;
        $this->promoProductFactory = $promoProductFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareData($ruleMetadata, $items, $metadataRules)
    {
        $totalQty = $this->qtyResolver->resolveQtyToDiscountByRule($ruleMetadata->getRule(), $items);
        if ($totalQty) {
            $this->attachPromoProducts($ruleMetadata, $items, $totalQty);
        }
    }

    /**
     * Retrieve promo products data
     *
     * @param RuleMetadataInterface $ruleMetadata
     * @param AbstractItem[] $items
     * @param float $totalQty
     * @return void
     * @throws \Exception
     */
    protected function attachPromoProducts($ruleMetadata, $items, $totalQty)
    {
        $promoProducts = [];
        $rule = $ruleMetadata->getRule();
        $availableQtyToGive = $this->qtyResolver->resolveQtyToGive($rule, $totalQty, $items);
        foreach ($rule->getPromoProducts() as $promoProduct) {
            if (!$this->validator->isAvailableProduct($promoProduct->getProductSku())) {
                continue;
            }
            $candidate = $this->resolveCandidate($availableQtyToGive, $promoProduct);
            if ($candidate) {
                $qty = $this->qtyResolver->resolveQtyToDiscountByStock($candidate, $rule);
                if ($qty > 0) {
                    $candidate->setQty($qty);
                    $promoProducts[] = $candidate;
                }
            }
        }

        $ruleMetadata
            ->setAvailableQtyToGive($availableQtyToGive)
            ->setPromoProducts($promoProducts);
    }

    /**
     * Resolve candidate
     *
     * @param float $availableQtyToGive
     * @param RulePromoProductInterface $promoProduct
     * @return RuleMetadataPromoProductInterface|null
     */
    protected function resolveCandidate($availableQtyToGive, $promoProduct)
    {
        $candidate = null;
        if ($availableQtyToGive > 0) {
            $option = $promoProduct->getOption();
            $candidate = $this->promoProductFactory->create(
                [
                    RuleMetadataPromoProductInterface::UNIQUE_KEY => null,
                    RuleMetadataPromoProductInterface::PRODUCT_SKU => $promoProduct->getProductSku(),
                    RuleMetadataPromoProductInterface::QTY => $availableQtyToGive
                ]
            );
            $candidate->setOption($option);
        }
        return $candidate;
    }
}
