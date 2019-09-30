<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver;

use Aheadworks\Afptc\Api\Data\CartItemRuleInterface;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\ScenarioPool;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProductPool;
use Aheadworks\Afptc\Model\Rule\RuleMetadataManager;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Metadata\Rule as RuleMetadata;

/**
 * Class Qty
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver
 */
class Qty
{
    /**
     * @var RuleMetadataManager
     */
    private $ruleMetadataManager;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var StockProductPool
     */
    private $stockProductPool;

    /**
     * @var ScenarioPool
     */
    private $scenarioPool;

    /**
     * @param RuleMetadataManager $ruleMetadataManager
     * @param ProductRepositoryInterface $productRepository
     * @param StockProductPool $stockProductPool
     * @param ScenarioPool $scenarioPool
     */
    public function __construct(
        RuleMetadataManager $ruleMetadataManager,
        ProductRepositoryInterface $productRepository,
        StockProductPool $stockProductPool,
        ScenarioPool $scenarioPool
    ) {
        $this->ruleMetadataManager = $ruleMetadataManager;
        $this->productRepository = $productRepository;
        $this->stockProductPool = $stockProductPool;
        $this->scenarioPool = $scenarioPool;
    }

    /**
     * Resolve qty to discount by rule
     *
     * @param RuleInterface $rule
     * @param AbstractItem[] $items
     * @return float
     * @throws \Exception
     */
    public function resolveQtyToDiscountByRule($rule, $items)
    {
        $processor = $this->scenarioPool->getScenarioProcessor($rule->getScenario());
        return $processor->getQtyToDiscountByRule($rule, $items);
    }

    /**
     * Resolve qty to discount by rule
     *
     * @param RuleMetadataPromoProductInterface $promoProduct
     * @param RuleInterface $rule
     * @return float
     * @throws \Exception
     */
    public function resolveQtyToDiscountByStock($promoProduct, $rule)
    {
        $availableQty = 0;
        $storeId = $rule->getStoreId();
        try {
            $product = $this->productRepository->get($promoProduct->getProductSku(), false, $storeId);
            $stockProduct = $this->stockProductPool->getStockProduct($product->getTypeId());
            if ((!$rule->isInStockOfferOnly()
                && $stockProduct->isBackOrderAvailable($promoProduct, $storeId))
            || !$stockProduct->isManageStockEnabled($promoProduct, $storeId)
            ) {
                $availableQty = $promoProduct->getQty();
            } else {
                $availableQty = $stockProduct->getAvailableQty($promoProduct, $storeId);
            }
        } catch (NoSuchEntityException $e) {
        }

        return max(0, min($availableQty, $promoProduct->getQty()));
    }

    /**
     * Resolve qty item by metadata rules
     *
     * @param RuleMetadata[] $metadataRules
     * @param RuleInterface $rule
     * @param AbstractItem $item
     * @param float $qtyToGiveLeft
     * @return float
     */
    public function resolveQtyItemByMetadataRules($metadataRules, $rule, $item, $qtyToGiveLeft)
    {
        $qtyWithDiscount = $this->ruleMetadataManager->getPromoProductQty($metadataRules, $item->getAwAfptcId());
        $availableProductQty = max(0, ($item->getTotalQty() - $qtyWithDiscount));
        $requestQty = $this->getRequestQty($item, $rule->getRuleId());

        return min($availableProductQty, $requestQty, $qtyToGiveLeft);
    }

    /**
     * Resolve qty to give
     *
     * @param RuleInterface $rule
     * @param float $totalQty
     * @param AbstractItem[]|null $items
     * @return int
     */
    public function resolveQtyToGive($rule, $totalQty, $items = null)
    {
        $qtyWithDiscount = 0;
        if ($items) {
            foreach ($items as $item) {
                if ($appliedQty = $this->getAppliedQty($item, $rule->getRuleId())) {
                    $qtyWithDiscount += $appliedQty;
                }
            }
        }
        return max(0, $rule->getQtyToGive() * $totalQty - $qtyWithDiscount);
    }

    /**
     * Retrieve applied qty in item
     *
     * @param CartItemInterface|AbstractItem $item
     * @param int $ruleId
     * @return bool|float
     */
    public function getAppliedQty($item, $ruleId)
    {
        $cartRules = $item->getExtensionAttributes() && $item->getExtensionAttributes()->getAwAfptcRules()
            ? $item->getExtensionAttributes()->getAwAfptcRules()
            : [];

        /** @var CartItemRuleInterface $cartRule */
        foreach ($cartRules as $cartRule) {
            if ($cartRule->getRuleId() == $ruleId) {
                return $cartRule->getQty();
            }
        }
        return false;
    }

    /**
     * Retrieve requested qty in item
     *
     * @param CartItemInterface|AbstractItem $item
     * @param int $ruleId
     * @return float
     */
    public function getRequestQty($item, $ruleId)
    {
        $cartRules = $item->getExtensionAttributes() ? $item->getExtensionAttributes()->getAwAfptcRulesRequest() : [];
        /** @var CartItemRuleInterface $cartRule */
        foreach ($cartRules as $cartRule) {
            if ($cartRule->getRuleId() == $ruleId) {
                return $cartRule->getQty();
            }
        }
        return 0;
    }
}
