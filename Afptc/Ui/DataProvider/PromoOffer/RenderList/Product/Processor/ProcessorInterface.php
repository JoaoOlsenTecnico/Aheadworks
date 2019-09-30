<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;

/**
 * Interface ProcessorInterface
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor
 */
interface ProcessorInterface
{
    /**
     * Prepare product data
     *
     * @param RuleMetadataInterface $ruleMetadata
     * @param ProductInterface|Product $product
     * @param RuleMetadataPromoProductInterface $ruleMetadataPromoProduct
     * @param ProductRenderInterface $productRender
     * @return void
     */
    public function prepareProductData($product, $ruleMetadata, $ruleMetadataPromoProduct, $productRender);
}
