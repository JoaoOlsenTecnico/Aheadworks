<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Validator;

use Aheadworks\Afptc\Model\Rule\Condition\Loader as ConditionLoader;

/**
 * Class AbstractValidator
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer\Validator
 */
class AbstractValidator implements ValidatorInterface
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
     * {@inheritdoc}
     */
    public function isValidRule($rule, $address, $quoteItem = null)
    {
        $ruleConditions = $this->conditionLoader->loadCartCondition($rule);
        $isValid = $quoteItem
            ? $ruleConditions->validateByEntityId($quoteItem->getProductId())
            : $ruleConditions->validate($address);

        return $isValid;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidItems($items)
    {
        $isValid = false;
        foreach ($items as $item) {
            if (!$item->getAwAfptcIsPromo() && !$item->getParentItem()) {
                $isValid = true;
                break;
            }
        }
        return $isValid;
    }
}
