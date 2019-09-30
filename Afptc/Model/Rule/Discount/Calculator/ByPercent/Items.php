<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount\Calculator\ByPercent;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Processor;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Validator;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\ItemsCalculatorInterface;
use Aheadworks\Afptc\Model\Rule\RuleMetadataManager;

/**
 * Class Items
 *
 * @package Aheadworks\Afptc\Model\Rule\Discount\Calculator\ByPercent
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
     * @param Validator $validator
     * @param Processor $processor
     * @param RuleMetadataManager $ruleMetadataManager
     */
    public function __construct(
        Validator $validator,
        Processor $processor,
        RuleMetadataManager $ruleMetadataManager
    ) {
        $this->validator = $validator;
        $this->processor = $processor;
        $this->ruleMetadataManager = $ruleMetadataManager;
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

            $amount = $this->calculateDiscount($itemPrice, $metadataRule);
            $baseAmount = $this->calculateDiscount($baseItemPrice, $metadataRule);

            $metadataRuleDiscountItem
                ->addAmount($amount)
                ->addBaseAmount($baseAmount)
                ->addPercent($this->getRulePercent($metadataRule), $qtyToDiscount)
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
        $rulePercent = $this->getRulePercent($metadataRule);
        $rulePrc = $rulePercent / 100;

        return $price * $rulePrc;
    }

    /**
     * Retrieve rule percent
     *
     * @param RuleMetadataInterface $metadataRule
     * @return int|float
     */
    private function getRulePercent($metadataRule)
    {
        return min(100, $metadataRule->getRule()->getDiscountAmount());
    }
}
