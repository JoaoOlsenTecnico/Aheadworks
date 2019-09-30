<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Interface ScenarioInterface
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty
 */
interface ScenarioInterface
{
    /**
     * Retrieve qty to discount by rule
     *
     * @param RuleInterface $rule
     * @param AbstractItem[] $items
     * @return float
     */
    public function getQtyToDiscountByRule($rule, $items);
}
