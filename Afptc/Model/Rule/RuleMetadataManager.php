<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;

/**
 * Class RuleMetadataManager
 *
 * @package Aheadworks\Afptc\Model\Rule
 */
class RuleMetadataManager
{
    /**
     * Retrieve promo product qty by $uniqueKey
     *
     * @param RuleMetadataInterface|RuleMetadataInterface[] $metadataRules
     * @param string $uniqueKey
     * @return int
     */
    public function getPromoProductQty($metadataRules, $uniqueKey)
    {
        $qtyWithDiscount = 0;
        $metadataRules = !is_array($metadataRules) ? [$metadataRules] : $metadataRules;
        foreach ($metadataRules as $metadataRule) {
            foreach ($metadataRule->getPromoProducts() as $promoProduct) {
                if ($promoProduct->getUniqueKey() == $uniqueKey) {
                    $qtyWithDiscount += $promoProduct->getQty();
                }
            }
        }

        return $qtyWithDiscount;
    }

    /**
     * Check if $uniqueKey is promo product or not
     *
     * @param RuleMetadataInterface $metadataRule
     * @param int $uniqueKey
     * @return bool
     */
    public function isPromoProduct($metadataRule, $uniqueKey)
    {
        foreach ($metadataRule->getPromoProducts() as $promoProduct) {
            if ($promoProduct->getUniqueKey() == $uniqueKey) {
                return true;
            }
        }
        return false;
    }
}
