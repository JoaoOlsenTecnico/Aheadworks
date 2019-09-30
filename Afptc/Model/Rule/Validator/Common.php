<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Validator;

use Aheadworks\Afptc\Model\Source\Rule\Discount\Type;
use Magento\Framework\Validator\AbstractValidator;
use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class Common
 *
 * @package Aheadworks\Afptc\Model\Rule\Validator
 */
class Common extends AbstractValidator
{
    /**
     * Returns true if entity meets the validation requirements
     *
     * @param RuleInterface $rule
     * @return bool
     */
    public function isValid($rule)
    {
        if (empty($rule->getName())) {
            $this->_addMessages([__('Rule name is required.')]);
        }

        if (empty($rule->getWebsiteIds())) {
            $this->_addMessages([__('Please specify a website.')]);
        }

        if (empty($rule->getCustomerGroupIds()) && $rule->getCustomerGroupIds() !== 0) {
            $this->_addMessages([__('Please specify Customer Groups.')]);
        }

        if (empty($rule->getPriority()) && $rule->getPriority() !== 0) {
            $this->_addMessages([__('Rule priority is required.')]);
        }

        if ($rule->getDiscountType() === Type::PERCENT
            && (empty($rule->getDiscountAmount()) && $rule->getDiscountAmount() !== 0)
        ) {
            $this->_addMessages([__('Discount amount is required.')]);
        }

        if ($rule->getDiscountType() === Type::PERCENT
            && (($rule->getDiscountAmount() < 0) || ($rule->getDiscountAmount() > 100))
        ) {
            $this->_addMessages([__('Discount must not be less than 0 or greater than 100.')]);
        }

        if ($rule->getDiscountType() === Type::FIXED_PRICE && ($rule->getDiscountAmount() < 0)) {
            $this->_addMessages([__('Discount must not be less than 0.')]);
        }

        if (empty($rule->getPromoProducts())) {
            $this->_addMessages([__('Please select promo products to offer.')]);
        }

        return empty($this->getMessages());
    }
}
