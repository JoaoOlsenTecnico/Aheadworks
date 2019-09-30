<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\Component\Form\Rule\Element\DiscountType;

use Aheadworks\Afptc\Model\Source\Rule\Discount\Type;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Options
 * @package Aheadworks\Afptc\Ui\Component\Form\Rule\Element\DiscountType
 */
class Options implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => Type::PERCENT, 'label' => __('WITH A PERCENT DISCOUNT')],
            ['value' => Type::FIXED_PRICE, 'label' => __('FOR A FIXED AMOUNT')],
        ];
    }
}
