<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount\Calculator\ByFixedPrice;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Processor;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Validator;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\ItemsCalculatorInterface;
use Aheadworks\Afptc\Model\Rule\RuleMetadataManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Items
 * @package Aheadworks\Afptc\Model\Rule\Discount\Calculator\ByFixedPrice
 */
class Items implements ItemsCalculatorInterface
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * @var RuleMetadataManager
     */
    private $ruleMetadataManager;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var array
     */
    private $percents = [];

    /**
     * @param Validator $validator
     * @param Processor $processor
     * @param RuleMetadataManager $ruleMetadataManager
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Validator $validator,
        Processor $processor,
        RuleMetadataManager $ruleMetadataManager,
        StoreManagerInterface $storeManager
    ) {
        $this->validator = $validator;
        $this->processor = $processor;
        $this->ruleMetadataManager = $ruleMetadataManager;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function calculate($items, $metadataRule, $metadataRuleDiscount)
    {
        foreach ($items as $item) {
            if (!$this->validator->canApplyDiscount($item, $metadataRule)) {
                continue;
            }
            $itemId = $item->getAwAfptcId();
            $metadataRuleDiscountItem = $metadataRuleDiscount->getItemById($itemId, true);

            $qtyToDiscount = $this->ruleMetadataManager->getPromoProductQty($metadataRule, $itemId);
            $itemPrice = $this->processor->getTotalItemPrice($item, $qtyToDiscount);
            $baseItemPrice = $this->processor->getTotalItemBasePrice($item, $qtyToDiscount);
            $metadataRule->setQuoteItem($item);

            $amount = $this->calculateDiscount($itemPrice, $metadataRule);
            $baseAmount = $this->calculateDiscount($baseItemPrice, $metadataRule);

            $metadataRuleDiscountItem
                ->addAmount($amount)
                ->addBaseAmount($baseAmount)
                ->addPercent($this->getRulePercent($metadataRule, $amount), $qtyToDiscount)
                ->addQtyByRule($qtyToDiscount, $metadataRule->getRule()->getRuleId())
                ->setItem($item);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function calculatePrice($price, $metadataRule)
    {
        return $price - $this->calculateDiscount($price, $metadataRule);
    }

    /**
     * {@inheritdoc}
     */
    public function calculateDiscount($price, $metadataRule)
    {
        $rulePercent = $this->getRulePercent($metadataRule, $price);
        $rulePrc = $rulePercent / 100;

        return $price * $rulePrc;
    }

    /**
     * Retrieve rule percent
     *
     * @param RuleMetadataInterface $metadataRule
     * @param float $price
     * @return int|float
     */
    private function getRulePercent($metadataRule, $price)
    {
        $quoteItem = $metadataRule->getQuoteItem();
        $discountAmount = $metadataRule->getRule()->getDiscountAmount();

        if ($quoteItem) {
            $key = $quoteItem->getId() . '_' . $metadataRule->getRule()->getRuleId();
            if (!isset($this->percents[$key])) {
                $percent = ($quoteItem->getBasePrice() - $discountAmount) / $quoteItem->getBasePrice() * 100;
                $this->percents[$key] = min(100, $percent);
            }
            return $this->percents[$key];
        }
        try {
            /** @var Store $store */
            $store = $this->storeManager->getStore();
            $discountAmount = $store->getCurrentCurrencyRate() * $discountAmount;
        } catch (NoSuchEntityException $e) {
        }
        $percent = ($price - $discountAmount) / $price * 100;

        return min(100, $percent);
    }
}
