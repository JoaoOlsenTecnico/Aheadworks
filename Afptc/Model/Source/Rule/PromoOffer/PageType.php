<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Source\Rule\PromoOffer;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class PageType
 *
 * @package Aheadworks\Afptc\Model\Source\Rule\PromoOffer
 */
class PageType implements OptionSourceInterface
{
    /**#@+
     * Page types
     */
    const DEFAULT = 'default';
    const CHECKOUT_PAGE = 'checkout';
    /**#@-*/

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::DEFAULT,
                'label' => __('As soon as cart content matches a rule')
            ],
            [
                'value' => self::CHECKOUT_PAGE,
                'label' => __('On a cart and checkout pages only')
            ],
        ];
    }
}
