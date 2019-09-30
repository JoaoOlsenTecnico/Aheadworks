<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor;

/**
 * Class Common
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor
 */
class Common implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepareProductData($product, $ruleMetadata, $ruleMetadataPromoProduct, $productRender)
    {
        $productRender
            ->setKey(uniqid())
            ->setChecked(false)
            ->setQty(0)
            ->setSku($product->getSku())
            ->setName($product->getName())
            ->setUrl($product->getUrlModel()->getUrl($product))
            ->setType($product->getTypeId())
            ->setIsSalable($product->isSalable());
    }
}
