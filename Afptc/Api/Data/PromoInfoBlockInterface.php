<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface PromoInfoBlockInterface
 * @api
 */
interface PromoInfoBlockInterface extends ExtensibleDataInterface
{
    /**
     * Constant for key of data array.
     */
    const PROMO = 'promo';

    /**
     * Get promo
     *
     * @return \Aheadworks\Afptc\Api\Data\PromoInterface
     */
    public function getPromo();

    /**
     * Set promo
     *
     * @param \Aheadworks\Afptc\Api\Data\PromoInterface $promo
     * @return $this
     */
    public function setPromo($promo);
}
