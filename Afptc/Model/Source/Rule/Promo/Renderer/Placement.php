<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Source\Rule\Promo\Renderer;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Placement
 *
 * @package Aheadworks\Afptc\Model\Source\Rule\Promo\Renderer
 */
class Placement implements OptionSourceInterface
{
    /**#@+
     * Placement values
     */
    const PRODUCT_LIST = 'product_list';
    const PRODUCT_PAGE = 'product_page';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::PRODUCT_LIST,
                'label' => __('Product List')
            ],
            [
                'value' => self::PRODUCT_PAGE,
                'label' => __('Product Page')
            ],
        ];
    }
}
