<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount\Calculator;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Metadata\Rule\Discount as MetadataRuleDiscount;

/**
 * Interface ItemsCalculatorInterface
 *
 * @package Aheadworks\Afptc\Model\Rule\Discount\Calculator
 */
interface ItemsCalculatorInterface
{
    /**
     * Calculate items discount
     *
     * @param AbstractItem[] $items
     * @param RuleMetadataInterface $metadataRule
     * @param MetadataRuleDiscount $metadataRuleDiscount
     * @return $this
     */
    public function calculate($items, $metadataRule, $metadataRuleDiscount);

    /**
     * Calculate price
     *
     * @param int|float $price
     * @param RuleMetadataInterface $metadataRule
     * @return int|float
     */
    public function calculatePrice($price, $metadataRule);

    /**
     * Calculate discount
     *
     * @param int|float $price
     * @param RuleMetadataInterface $metadataRule
     * @return int|float
     */
    public function calculateDiscount($price, $metadataRule);
}
