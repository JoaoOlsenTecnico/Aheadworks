<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Rule\RuleMetadataManager;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Validator
 *
 * @package Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item
 */
class Validator
{
    /**
     * @var RuleMetadataManager
     */
    private $ruleMetadataManager;

    /**
     * @param RuleMetadataManager $ruleMetadataManager
     */
    public function __construct(
        RuleMetadataManager $ruleMetadataManager
    ) {
        $this->ruleMetadataManager = $ruleMetadataManager;
    }

    /**
     * Can apply discount on item
     *
     * @param AbstractItem $item
     * @param RuleMetadataInterface $metadataRule
     * @return bool
     */
    public function canApplyDiscount($item, $metadataRule)
    {
        return !$item->getParentItem()
            && $this->ruleMetadataManager->isPromoProduct($metadataRule, $item->getAwAfptcId());
    }
}
