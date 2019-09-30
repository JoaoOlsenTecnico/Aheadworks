<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty;

use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;

/**
 * Class StockProductInterface
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty
 */
interface StockProductInterface
{
    /**
     * Retrieve available qty in stock
     *
     * @param RuleMetadataPromoProductInterface $promoProduct
     * @param int $scopeId
     * @return float
     */
    public function getAvailableQty($promoProduct, $scopeId);

    /**
     * Check if manage stock enabled
     *
     * @param RuleMetadataPromoProductInterface $promoProduct
     * @param int $scopeId
     * @return bool
     */
    public function isManageStockEnabled($promoProduct, $scopeId) : bool;

    /**
     * Check if back order available
     *
     * @param RuleMetadataPromoProductInterface $promoProduct
     * @param int $scopeId
     * @return bool
     */
    public function isBackOrderAvailable($promoProduct, $scopeId) : bool;

    /**
     * @param RuleMetadataPromoProductInterface $promoProduct
     * @param int $scopeId
     * @return float
     */
    public function getAvailableQtyForIndex($promoProduct, $scopeId);
}
