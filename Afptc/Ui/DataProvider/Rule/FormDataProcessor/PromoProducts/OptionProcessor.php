<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts;

/**
 * Class OptionProcessor
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts
 */
class OptionProcessor implements ProcessorInterface
{
    /**
     * @var array[]
     */
    private $processors;

    /**
     * @param array $processors
     */
    public function __construct(array $processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareProductOptions($productData, $promoProduct, $product)
    {
        $defaultProductType = ProcessorInterface::DEFAULT_PRODUCT_TYPE;

        if (isset($this->processors[$product->getTypeId()])) {
            $productData = $this->processors[$product->getTypeId()]->prepareProductOptions(
                $productData,
                $promoProduct,
                $product
            );
        } elseif (isset($this->processors[$defaultProductType])) {
            $productData = $this->processors[$defaultProductType]->prepareProductOptions(
                $productData,
                $promoProduct,
                $product
            );
        }

        return $productData;
    }
}
