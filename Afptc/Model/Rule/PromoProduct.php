<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule;

use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class PromoProduct
 *
 * @package Aheadworks\Afptc\Model\Rule
 */
class PromoProduct extends AbstractExtensibleObject implements RulePromoProductInterface
{
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
    public function getPosition()
    {
        return $this->_get(self::POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
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
        \Aheadworks\Afptc\Api\Data\RulePromoProductExtensionInterface $extensionAttributes
    ) {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
