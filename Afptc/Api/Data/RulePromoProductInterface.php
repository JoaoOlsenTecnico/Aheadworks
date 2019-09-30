<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface RulePromoProductInterface
 * @api
 */
interface RulePromoProductInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const PRODUCT_SKU = 'product_sku';
    const OPTION = 'option';
    const POSITION = 'position';
    /**#@-*/

    /**
     * Get product sku
     *
     * @return string
     */
    public function getProductSku();

    /**
     * Set product sku
     *
     * @param string $productSku
     * @return $this
     */
    public function setProductSku($productSku);

    /**
     * Get option
     *
     * @return \Magento\Quote\Api\Data\ProductOptionInterface|null
     */
    public function getOption();

    /**
     * Set option
     *
     * @param \Magento\Quote\Api\Data\ProductOptionInterface $option
     * @return $this
     */
    public function setOption($option);

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition();

    /**
     * Set position
     *
     * @param int $position
     * @return $this
     */
    public function setPosition($position);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Afptc\Api\Data\RulePromoProductExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Afptc\Api\Data\RulePromoProductExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\RulePromoProductExtensionInterface $extensionAttributes
    );
}
