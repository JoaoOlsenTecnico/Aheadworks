<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts\ProductType;

use Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts\ProcessorInterface;

/**
 * Class DefaultType
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts\ProductType
 */
class DefaultType implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepareProductOptions($productData, $promoProduct, $product)
    {
        return $productData;
    }
}
