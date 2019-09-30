<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer;

use Aheadworks\Afptc\Model\Rule\PromoOffer\Validator\Pool as ValidatorPool;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Validator
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer
 */
class Validator
{
    /**
     * @var ValidatorPool
     */
    private $validatorPool;

    /**
     * @param ValidatorPool $validatorPool
     */
    public function __construct(
        ValidatorPool $validatorPool
    ) {
        $this->validatorPool = $validatorPool;
    }

    /**
     * Retrieve valid rules
     *
     * @param RuleInterface[] $rules
     * @param Address $address
     * @param AbstractItem[] $items
     * @param AbstractItem|null $lastQuoteItem
     * @return RuleInterface[]
     * @throws \Exception
     */
    public function getValidRules($rules, $address, $items, $lastQuoteItem = null)
    {
        $validRules = [];
        foreach ($rules as $rule) {
            $validator = $this->validatorPool->getValidator($rule->getScenario());
            if ($validator->isValidItems($items)
                && $validator->isValidRule($rule, $address, $lastQuoteItem)
            ) {
                $validRules[] = $rule;

                if ($rule->isStopRulesProcessing()) {
                    break;
                }
            }
        }
        return $validRules;
    }
}
