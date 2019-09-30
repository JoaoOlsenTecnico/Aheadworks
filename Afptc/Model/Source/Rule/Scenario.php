<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Source\Rule;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Scenario
 *
 * @package Aheadworks\Afptc\Model\Source\Rule
 */
class Scenario implements OptionSourceInterface
{
    /**#@+
     * Rule scenarios
     */
    const BUY_X_GET_Y = 'buy_x_get_y';
    const SPEND_X_GET_Y = 'spend_x_get_y';
    const COUPON = 'coupon';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::BUY_X_GET_Y, 'label' => __('Buy X Get Y')],
            ['value' => self::SPEND_X_GET_Y, 'label' => __('Spend X Get Y')],
            ['value' => self::COUPON, 'label' => __('Coupon')],
        ];
    }
}
