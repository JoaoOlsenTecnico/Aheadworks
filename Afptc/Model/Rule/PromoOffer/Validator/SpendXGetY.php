<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Validator;

/**
 * Class SpendXGetY
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer\Validator
 */
class SpendXGetY extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function isValidRule($rule, $address, $quoteItem = null)
    {
        return parent::isValidRule($rule, $address);
    }
}
