<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface RuleMetadataPromoProductInterface
 * @api
 */
interface RuleMetadataPromoProductInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const UNIQUE_KEY = 'unique_key';
    const PRODUCT_SKU = 'product_sku';
    const QTY = 'qty';
    const OPTION = 'option';
    /**#@-*/

    /**
     * Get unique key
     *
     * @return string
     */
    public function getUniqueKey();

    /**
     * Set unique key
     *
     * @param string $uniqueKey
     * @return $this
     */
    public function setUniqueKey($uniqueKey);

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
     * Get qty
     *
     * @return float
     */
    public function getQty();

    /**
     * Set qty
     *
     * @param float $qty
     * @return $this
     */
    public function setQty($qty);

    /**
     * Get option
     *
     * @return \Magento\Quote\Api\Data\ProductOptionInterface
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
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductExtensionInterface $extensionAttributes
    );
}
