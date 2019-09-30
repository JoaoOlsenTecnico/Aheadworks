<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Validator;

use Magento\Framework\Validator\AbstractValidator;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\Source\Rule\Scenario;

/**
 * Class Coupon
 *
 * @package Aheadworks\Afptc\Model\Rule\Validator
 */
class Coupon extends AbstractValidator
{
    /**
     * Returns true if coupon code is valid
     *
     * @param RuleInterface $rule
     * @return bool
     */
    public function isValid($rule)
    {
        if ($rule->getScenario() == Scenario::COUPON) {
            $couponCode = $rule->getCouponCode();
            if (!$couponCode) {
                $this->_addMessages([__('Coupon code must not be empty.')]);
            }
        }
        return empty($this->getMessages());
    }
}
