<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor;

/**
 * Class Price
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor
 */
class Price implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $priceProviders = [];

    /**
     * @param array $priceProviders
     */
    public function __construct(array $priceProviders = [])
    {
        $this->priceProviders = $priceProviders;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareProductData($product, $ruleMetadata, $ruleMetadataPromoProduct, $productRender)
    {
        foreach ($this->priceProviders as $provider) {
            $provider->prepareProductData($product, $ruleMetadata, $ruleMetadataPromoProduct, $productRender);
        }
    }
}
