<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

/**
 * Interface CheckoutSummaryProductImageInterface
 * @api
 */
interface CheckoutSummaryProductImageInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const SRC = 'src';
    const ALT = 'alt';
    const HEIGHT = 'height';
    const WIDTH = 'width';
    /**#@-*/

    /**
     * Get image src
     *
     * @return string|null
     */
    public function getSrc();

    /**
     * Set image src
     *
     * @param string $imageSrc
     * @return $this
     */
    public function setSrc($imageSrc);

    /**
     * Get image alt
     *
     * @return string|null
     */
    public function getAlt();

    /**
     * Set image alt
     *
     * @param string $imageAlt
     * @return $this
     */
    public function setAlt($imageAlt);

    /**
     * Get image width
     *
     * @return int|null
     */
    public function getWidth();

    /**
     * Set image width
     *
     * @param string $imageWidth
     * @return $this
     */
    public function setWidth($imageWidth);

    /**
     * Get image height
     *
     * @return int|null
     */
    public function getHeight();

    /**
     * Set image height
     *
     * @param string $imageHeight
     * @return $this
     */
    public function setHeight($imageHeight);
}
