<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Source\Product;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\Product\Type as DefaultProduct;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProduct;
use Magento\Downloadable\Model\Product\Type as DownloadableProduct;

/**
 * Class AllowedType
 *
 * @package Aheadworks\Afptc\Model\Source\Product
 */
class AllowedType implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => DefaultProduct::TYPE_SIMPLE, 'label' => __('Simple Product')],
            ['value' => DefaultProduct::TYPE_VIRTUAL, 'label' => __('Virtual Product')],
            ['value' => ConfigurableProduct::TYPE_CODE, 'label' => __('Configurable Product')],
            ['value' => DownloadableProduct::TYPE_DOWNLOADABLE, 'label' => __('Downloadable Product')],
        ];
    }

    /**
     * Get list of allowed products for promo
     *
     * @return array
     */
    public function getTypeList()
    {
        return [
            DefaultProduct::TYPE_SIMPLE,
            DefaultProduct::TYPE_VIRTUAL,
            ConfigurableProduct::TYPE_CODE,
            DownloadableProduct::TYPE_DOWNLOADABLE
        ];
    }
}
