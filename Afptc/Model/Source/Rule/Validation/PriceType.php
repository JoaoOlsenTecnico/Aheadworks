<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Source\Rule\Validation;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class PriceType
 *
 * @package Aheadworks\Afptc\Model\Source\Rule\Validation
 */
class PriceType implements OptionSourceInterface
{
    /**#@+
     * Price types
     */
    const EXCLUDING_TAX = 0;
    const INCLUDING_TAX = 1;
    /**#@-*/

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::EXCLUDING_TAX,
                'label' => __('Excluding Tax')
            ],
            [
                'value' => self::INCLUDING_TAX,
                'label' => __('Including Tax')
            ],
        ];
    }
}
