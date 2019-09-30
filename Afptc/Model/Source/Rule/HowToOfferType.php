<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Source\Rule;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class HowToOfferType
 *
 * @package Aheadworks\Afptc\Model\Source\Rule\Popup
 */
class HowToOfferType implements OptionSourceInterface
{
    /**#@+
     * Pop up how to offer types
     */
    const SHOW_POPUP = 'show_popup';
    const AUTO_ADDING = 'auto_adding';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::SHOW_POPUP, 'label' => __('Show Popup')],
            ['value' => self::AUTO_ADDING, 'label' => __('Auto-add promo product to cart, if possible')]
        ];
    }
}
