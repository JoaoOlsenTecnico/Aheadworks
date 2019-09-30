<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRender;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ImageInterface
 * @api
 */
interface ImageInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const SRC = 'src';
    const ALT = 'alt';
    const WIDTH = 'width';
    const HEIGHT = 'height';
    /**#@-*/

    /**
     * Get src
     *
     * @return string
     */
    public function getSrc();

    /**
     * Set src
     *
     * @param string $src
     * @return $this
     */
    public function setSrc($src);

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt();

    /**
     * Set alt
     *
     * @param string $alt
     * @return $this
     */
    public function setAlt($alt);

    /**
     * Get width
     *
     * @return float
     */
    public function getWidth();

    /**
     * Set width
     *
     * @param float $width
     * @return $this
     */
    public function setWidth($width);

    /**
     * Get height
     *
     * @return float
     */
    public function getHeight();

    /**
     * Set height
     *
     * @param float $height
     * @return $this
     */
    public function setHeight($height);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRender\ImageExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRender\ImageExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRender\ImageExtensionInterface $extensionAttributes
    );
}
