<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface RuleMetadataInterface
 * @api
 */
interface RuleMetadataInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const RULE = 'rule';
    const PROMO_PRODUCTS = 'promo_products';
    const AVAILABLE_QTY_TO_GIVE = 'available_qty_to_give';
    const QUOTE_ITEM = 'quote_item';
    /**#@-*/

    /**
     * Get Rule
     *
     * @return \Aheadworks\Afptc\Api\Data\RuleInterface
     */
    public function getRule();

    /**
     * Set Rule
     *
     * @param \Aheadworks\Afptc\Api\Data\RuleInterface $rule
     * @return $this
     */
    public function setRule($rule);

    /**
     * Get promo products
     *
     * @return \Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface[]
     */
    public function getPromoProducts();

    /**
     * Set discount type
     *
     * @param \Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface[] $promoProducts
     * @return $this
     */
    public function setPromoProducts($promoProducts);

    /**
     * Get available qty to give
     *
     * @return int
     */
    public function getAvailableQtyToGive();

    /**
     * Set available qty to give
     *
     * @param int $availableQtyToGive
     * @return $this
     */
    public function setAvailableQtyToGive($availableQtyToGive);

    /**
     * Get quote item
     * Necessary for fixed amount rule type
     *
     * @return \Magento\Quote\Model\Quote\Item\AbstractItem|null
     */
    public function getQuoteItem();

    /**
     * Set quote item
     *
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $quoteItem
     * @return $this
     */
    public function setQuoteItem($quoteItem);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Afptc\Api\Data\RuleMetadataExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Afptc\Api\Data\RuleMetadataExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\RuleMetadataExtensionInterface $extensionAttributes
    );
}
