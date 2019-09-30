<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Checkout\Summary\Item;

use Aheadworks\Afptc\Api\Data\CheckoutSummaryProductImageInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class ImageData
 *
 * @package Aheadworks\Afptc\Model\Checkout\Summary\Item
 */
class ImageData extends AbstractExtensibleObject implements CheckoutSummaryProductImageInterface
{
    /**
     * {@inheritdoc}
     */
    public function setSrc($imageSrc)
    {
        return $this->setData(self::SRC, $imageSrc);
    }

    /**
     * {@inheritdoc}
     */
    public function getSrc()
    {
        return $this->_get(self::SRC);
    }

    /**
     * {@inheritdoc}
     */
    public function setAlt($imageAlt)
    {
        return $this->setData(self::ALT, $imageAlt);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlt()
    {
        return $this->_get(self::ALT);
    }

    /**
     * {@inheritdoc}
     */
    public function setHeight($imageHeight)
    {
        return $this->setData(self::HEIGHT, $imageHeight);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->_get(self::HEIGHT);
    }

    /**
     * {@inheritdoc}
     */
    public function setWidth($imageWidth)
    {
        return $this->setData(self::WIDTH, $imageWidth);
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->_get(self::WIDTH);
    }
}
