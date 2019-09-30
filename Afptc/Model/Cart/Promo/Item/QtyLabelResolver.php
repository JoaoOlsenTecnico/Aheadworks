<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Cart\Promo\Item;

use Aheadworks\Afptc\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class QtyLabelResolver
 *
 * @package Aheadworks\Afptc\Model\Cart\Promo\Item
 */
class QtyLabelResolver
{
    /**
     * Resolve qty label for quote item
     *
     * @param AbstractItem|CartItemInterface $item
     * @return string
     */
    public function resolve($item)
    {
        $label = '';
        $afptcQty = $item->getAwAfptcQty() * 1;
        if ($item->getAwAfptcIsPromo() && $afptcQty > 0) {
            $qty = $item->getQty();
            $discountPercent = $item->getAwAfptcPercent() * 1;
            $isFree = $discountPercent == 100;
            $afptcRules = $item->getExtensionAttributes()->getAwAfptcRules();

            if ($qty == 1 && $afptcQty == 1) {
                $freePhrase = 'Free';
                $discountedPhrase = '%discount% off';
            } elseif ($qty >= $afptcQty && count($afptcRules) == 1) {
                if ($afptcQty == 1) {
                    $freePhrase = '1 is free';
                    $discountedPhrase = '1 is %discount% off';
                } else {
                    $freePhrase = '%qty are free';
                    $discountedPhrase = '%qty are %discount% off';
                }
            } else {
                $freePhrase = $discountedPhrase = __('Items have different discounts');
            }
            $phrase = $isFree ? $freePhrase : $discountedPhrase;
            $label = __($phrase, ['qty' => $afptcQty, 'discount' => round($discountPercent, 2)]);
        }
        return $label;
    }
}
