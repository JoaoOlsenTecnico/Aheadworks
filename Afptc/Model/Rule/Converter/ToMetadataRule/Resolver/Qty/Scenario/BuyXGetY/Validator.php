<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\Scenario\BuyXGetY;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Bundle\Model\Product\Type;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Rule\Condition\Loader as ConditionLoader;

/**
 * Class Validator
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\Scenario\BuyXGetY
 */
class Validator
{
    /**
     * @var ConditionLoader
     */
    private $conditionLoader;

    /**
     * @param ConditionLoader $conditionLoader
     */
    public function __construct(
        ConditionLoader $conditionLoader
    ) {
        $this->conditionLoader = $conditionLoader;
    }

    /**
     * Check if item is valid
     *
     * @param AbstractItem $item
     * @param RuleInterface $rule
     * @return bool
     */
    public function isValidItem($item, $rule)
    {
        if ($item->getAwAfptcIsPromo() || $item->getParentItem()) {
            $isValid = false;
        } else {
            $ruleConditions = $this->conditionLoader->loadCartCondition($rule);
            $children = $item->getChildren();
            $isValid = $ruleConditions->validateByEntityId($item->getProductId());
            if (!$isValid && $children) {
                foreach ($children as $child) {
                    if ($ruleConditions->validateByEntityId($child->getProductId())) {
                        $isValid = true;
                        break;
                    }
                }
            }
        }
        return $isValid;
    }
}
