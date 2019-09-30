<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Source\Rule\Discount;

/**
 * Class Type
 *
 * @package Aheadworks\Afptc\Model\Source\Rule\Discount
 */
class Type
{
    /**#@+
     * Discount types
     */
    const PERCENT = 'percent';
    const FIXED_PRICE = 'fixed_price';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PERCENT, 'label' => __('Percent')],
            ['value' => self::FIXED_PRICE, 'label' => __('Fixed Price')],
        ];
    }
}
