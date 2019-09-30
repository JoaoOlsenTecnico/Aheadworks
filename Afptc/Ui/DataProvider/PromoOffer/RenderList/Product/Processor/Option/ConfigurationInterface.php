<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;

/**
 * Interface ConfigurationInterface
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Configuration
 */
interface ConfigurationInterface
{
    /**
     * Get options array
     *
     * @param ProductInterface|Product $product
     * @return array
     */
    public function getOptions($product);

    /**
     * Get options array by metadata
     *
     * @param ProductInterface|Product $product
     * @param RuleMetadataInterface $ruleMetadata
     * @param RuleMetadataPromoProductInterface $ruleMetadataPromoProduct
     * @return array
     */
    public function getOptionsByMetadata($product, $ruleMetadata, $ruleMetadataPromoProduct);

    /**
     * Get options type
     *
     * @param array $options
     * @return string
     */
    public function getOptionType($options);
}
