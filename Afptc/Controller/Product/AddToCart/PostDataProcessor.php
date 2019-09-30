<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Product\AddToCart;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Magento\Framework\Stdlib\BooleanUtils;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Option as OptionResolver;

/**
 * Class PostDataProcessor
 *
 * @package Aheadworks\Afptc\Controller\Product\AddToCart
 */
class PostDataProcessor
{
    /**
     * @var BooleanUtils
     */
    private $booleanUtils;

    /**
     * @var OptionResolver
     */
    private $optionResolver;

    /**
     * @param BooleanUtils $booleanUtils
     * @param OptionResolver $optionResolver
     */
    public function __construct(
        BooleanUtils $booleanUtils,
        OptionResolver $optionResolver
    ) {
        $this->booleanUtils = $booleanUtils;
        $this->optionResolver = $optionResolver;
    }

    /**
     * Prepare request items
     *
     * @param array $items
     * @return array
     */
    public function prepareRequestItems($items)
    {
        $preparedItems = [];
        foreach ($items as $item) {
            if (!isset($item[ProductRenderInterface::RULE_ID]) || !isset($item[ProductRenderInterface::SKU])
                || !isset($item[ProductRenderInterface::QTY]) || !isset($item[ProductRenderInterface::CHECKED])
                || (isset($item[ProductRenderInterface::CHECKED])
                    && !$this->booleanUtils->toBoolean($item[ProductRenderInterface::CHECKED]))
            ) {
                continue;
            }
            $preparedItems[$item[ProductRenderInterface::RULE_ID]][] = [
                'sku' => $item[ProductRenderInterface::SKU],
                'qty' => $item[ProductRenderInterface::QTY],
                'options' => [
                    'super_attribute' => isset($item['super_attribute']) ? $item['super_attribute'] : []
                ]
            ];
        }
        return $preparedItems;
    }

    /**
     * Prepare metadata rules by request items
     *
     * @param RuleMetadataInterface[] $metadataRules
     * @param array $itemsToCart
     * @return RuleMetadataInterface[]
     */
    public function prepareMetadataRulesByItems($metadataRules, $itemsToCart)
    {
        $preparedMetadataRules = [];
        foreach ($metadataRules as $ruleKey => $metadataRule) {
            if (!isset($itemsToCart[$metadataRule->getRule()->getRuleId()])) {
                continue;
            }
            $promoProducts = [];
            $items = $itemsToCart[$metadataRule->getRule()->getRuleId()];
            foreach ($metadataRule->getPromoProducts() as $promoProduct) {
                if ($matchItem = $this->find($promoProduct, $items)) {
                    $option = $this->optionResolver->resolveOptions($promoProduct, $matchItem['options']);
                    $promoProduct
                        ->setOption($option)
                        ->setQty($matchItem['qty']);
                    $promoProducts[] = $promoProduct;
                }
            }
            $metadataRule->setPromoProducts($promoProducts);
            $preparedMetadataRules[] = $metadataRule;
        }
        return $preparedMetadataRules;
    }

    /**
     * Find promo product in requested products
     *
     * @param RuleMetadataPromoProductInterface $promoProduct
     * @param array $items
     * @return array|bool
     */
    private function find($promoProduct, $items)
    {
        foreach ($items as $item) {
            if ($item['sku'] == $promoProduct->getProductSku()) {
                return $item;
            }
        }
        return false;
    }
}
