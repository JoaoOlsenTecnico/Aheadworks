<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor;

/**
 * Class Composite
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor
 */
class Composite implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * @param ProcessorInterface[] $processors
     */
    public function __construct(array $processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareProductData($product, $ruleMetadata, $ruleMetadataPromoProduct, $productRender)
    {
        foreach ($this->processors as $processor) {
            $processor->prepareProductData($product, $ruleMetadata, $ruleMetadataPromoProduct, $productRender);
        }
    }
}
