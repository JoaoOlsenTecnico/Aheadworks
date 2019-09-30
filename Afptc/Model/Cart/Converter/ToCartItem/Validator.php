<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Cart\Converter\ToCartItem;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;

/**
 * Class Validator
 * @package Aheadworks\Afptc\Model\Cart\Converter\ToCartItem
 */
class Validator
{
    /**
     * Check if valid metadata rule for add promo products to cart
     *
     * @param RuleMetadataInterface $metadataRule
     * @return bool
     */
    public function isValid($metadataRule)
    {
        $calculatedPromoProductsQty = $this->calculatePromoProductsQty($metadataRule->getPromoProducts());
        return $calculatedPromoProductsQty > 0 && $metadataRule->getAvailableQtyToGive() >= $calculatedPromoProductsQty;
    }

    /**
     * Calculate promo products qty
     *
     * @param RuleMetadataPromoProductInterface[] $promoProducts
     * @return float
     */
    private function calculatePromoProductsQty($promoProducts)
    {
        $qty = 0;
        foreach ($promoProducts as $promoProduct) {
            $qty += $promoProduct->getQty();
        }
        return $qty;
    }
}
