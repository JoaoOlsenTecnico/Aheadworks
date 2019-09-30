<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount\Calculator;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Metadata\Rule\Discount as MetadataRuleDiscount;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Api\Data\AddressInterface;

/**
 * Interface DiscountCalculatorInterface
 *
 * @package Aheadworks\Afptc\Model\Rule\Discount\Calculator
 */
interface DiscountCalculatorInterface
{
    /**
     * Calculate discount
     *
     * @param AbstractItem[] $items
     * @param AddressInterface $address
     * @param RuleMetadataInterface $metadataRule
     * @param MetadataRuleDiscount $metadataRuleDiscount
     * @return MetadataRuleDiscount
     */
    public function calculate($items, $address, $metadataRule, $metadataRuleDiscount);

    /**
     * Calculate price
     *
     * @param int|float $price
     * @param RuleMetadataInterface $metadataRule
     * @return int|float
     */
    public function calculatePrice($price, $metadataRule);
}
