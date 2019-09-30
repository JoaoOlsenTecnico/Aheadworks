<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;

/**
 * Interface ProcessorInterface
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts
 */
interface ProcessorInterface
{
    /**
     * Default product type.
     * It is used when other product type cannot be found
     */
    const DEFAULT_PRODUCT_TYPE = 'default';

    /**
     * Prepare product options
     *
     * @param array $productData
     * @param array $promoProduct
     * @param ProductInterface|Product $product
     * @return array
     */
    public function prepareProductOptions($productData, $promoProduct, $product);
}
