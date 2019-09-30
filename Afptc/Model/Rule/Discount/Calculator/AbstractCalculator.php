<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount\Calculator;

use Aheadworks\Afptc\Model\Metadata\Rule\Discount as MetadataRuleDiscount;

/**
 * Class AbstractCalculator
 *
 * @package Aheadworks\Afptc\Model\Rule\Discount\Calculator
 */
abstract class AbstractCalculator implements DiscountCalculatorInterface
{
    /**
     * @var ItemsCalculatorInterface
     */
    protected $itemsCalculator;

    /**
     * @param ItemsCalculatorInterface $itemsCalculator
     */
    public function __construct(
        ItemsCalculatorInterface $itemsCalculator
    ) {
        $this->itemsCalculator = $itemsCalculator;
    }

    /**
     * {@inheritdoc}
     */
    public function calculate($items, $address, $metadataRule, $metadataRuleDiscount)
    {
        $this->itemsCalculator->calculate($items, $metadataRule, $metadataRuleDiscount);
        $this->calculateItemAmount($metadataRuleDiscount);

        return $metadataRuleDiscount;
    }

    /**
     * {@inheritdoc}
     */
    public function calculatePrice($price, $metadataRule)
    {
        return $this->itemsCalculator->calculatePrice($price, $metadataRule);
    }

    /**
     * Calculate item amount
     *
     * @param MetadataRuleDiscount $metadataRuleDiscount
     * @return $this
     */
    protected function calculateItemAmount($metadataRuleDiscount)
    {
        $amount = $baseAmount = 0;
        foreach ($metadataRuleDiscount->getItems() as $metadataRuleDiscountItem) {
            $amount += $metadataRuleDiscountItem->getAmount();
            $baseAmount += $metadataRuleDiscountItem->getBaseAmount();
        }

        $metadataRuleDiscount
            ->setAmount($amount)
            ->setBaseAmount($baseAmount);

        return $this;
    }
}
