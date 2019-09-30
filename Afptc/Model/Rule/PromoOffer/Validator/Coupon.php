<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Validator;

/**
 * Class Coupon
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer\Validator
 */
class Coupon extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function isValidRule($rule, $address, $quoteItem = null)
    {
        $ruleCouponCode = strtolower($rule->getCouponCode());
        $quoteCouponCode = strtolower($address->getCouponCode());

        return !empty($ruleCouponCode) && !empty($quoteCouponCode)
            && $quoteCouponCode == $ruleCouponCode
            && parent::isValidRule($rule, $address, null);
    }
}
