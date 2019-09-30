<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Metadata;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class Rule
 *
 * @package Aheadworks\Afptc\Model\Metadata
 */
class Rule extends AbstractExtensibleObject implements RuleMetadataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRule()
    {
        return $this->_get(self::RULE);
    }

    /**
     * {@inheritdoc}
     */
    public function setRule($rule)
    {
        return $this->setData(self::RULE, $rule);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoProducts()
    {
        return $this->_get(self::PROMO_PRODUCTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoProducts($promoProducts)
    {
        return $this->setData(self::PROMO_PRODUCTS, $promoProducts);
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableQtyToGive()
    {
        return $this->_get(self::AVAILABLE_QTY_TO_GIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setAvailableQtyToGive($availableQtyToGive)
    {
        return $this->setData(self::AVAILABLE_QTY_TO_GIVE, $availableQtyToGive);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuoteItem()
    {
        return $this->_get(self::QUOTE_ITEM);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuoteItem($quoteItem)
    {
        return $this->setData(self::QUOTE_ITEM, $quoteItem);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\RuleMetadataExtensionInterface $extensionAttributes
    ) {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
