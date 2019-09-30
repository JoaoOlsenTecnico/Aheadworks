<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Metadata\Rule\Discount as MetadataRuleDiscount;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Pool as CalculatorPool;
use Aheadworks\Afptc\Model\Metadata\Rule\DiscountFactory as MetadataRuleDiscountFactory;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Distributor;

/**
 * Class Calculator
 *
 * @package Aheadworks\Afptc\Model\Rule\Discount
 */
class Calculator
{
    /**
     * @var MetadataRuleDiscountFactory
     */
    private $metadataRuleDiscountFactory;

    /**
     * @var CalculatorPool
     */
    private $calculatorPool;

    /**
     * @var Distributor
     */
    private $distributor;

    /**
     * @param MetadataRuleDiscountFactory $metadataRuleDiscountFactory
     * @param CalculatorPool $calculatorPool
     * @param Distributor $distributor
     */
    public function __construct(
        MetadataRuleDiscountFactory $metadataRuleDiscountFactory,
        CalculatorPool $calculatorPool,
        Distributor $distributor
    ) {
        $this->metadataRuleDiscountFactory = $metadataRuleDiscountFactory;
        $this->calculatorPool = $calculatorPool;
        $this->distributor = $distributor;
    }

    /**
     * Calculate discount
     *
     * @param CartItemInterface[]|AbstractItem[] $items
     * @param AddressInterface $address
     * @param RuleMetadataInterface[] $metadataRules
     * @return MetadataRuleDiscount
     */
    public function calculateDiscount($items, $address, $metadataRules)
    {
        $metadataRuleDiscount = $this->metadataRuleDiscountFactory->create();
        foreach ($metadataRules as $metadataRule) {
            $calculator = $this->calculatorPool->getCalculatorByType($metadataRule->getRule()->getDiscountType());
            $metadataRuleDiscount = $calculator->calculate($items, $address, $metadataRule, $metadataRuleDiscount);
        }

        foreach ($metadataRuleDiscount->getItems() as $item) {
            $this->distributor->distribute($item);
        }

        return $metadataRuleDiscount;
    }

    /**
     * Calculate price
     *
     * @param int|float $price
     * @param RuleMetadataInterface $metadataRule
     * @return int|float
     */
    public function calculatePrice($price, $metadataRule)
    {
        $calculator = $this->calculatorPool->getCalculatorByType($metadataRule->getRule()->getDiscountType());

        return $calculator->calculatePrice($price, $metadataRule);
    }
}
