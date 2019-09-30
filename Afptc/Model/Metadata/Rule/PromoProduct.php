<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Metadata\Rule;

use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class PromoProduct
 *
 * @package Aheadworks\Afptc\Model\Metadata\Rule
 */
class PromoProduct extends AbstractExtensibleObject implements RuleMetadataPromoProductInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUniqueKey()
    {
        return $this->_get(self::UNIQUE_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setUniqueKey($uniqueKey)
    {
        return $this->setData(self::UNIQUE_KEY, $uniqueKey);
    }

    /**
     * {@inheritdoc}
     */
    public function getQty()
    {
        return $this->_get(self::QTY);
    }

    /**
     * {@inheritdoc}
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * {@inheritdoc}
     */
    public function getProductSku()
    {
        return $this->_get(self::PRODUCT_SKU);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductSku($productSku)
    {
        return $this->setData(self::PRODUCT_SKU, $productSku);
    }

    /**
     * {@inheritdoc}
     */
    public function getOption()
    {
        return $this->_get(self::OPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($option)
    {
        return $this->setData(self::OPTION, $option);
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
        \Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductExtensionInterface $extensionAttributes
    ) {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
