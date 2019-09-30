<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor;

use Magento\Quote\Model\Quote\Address;

/**
 * Class Item
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor
 */
class Item implements ProcessorInterface
{
    /**
     * Remove promo products from address
     *
     * @param Address $address
     * @param array $data
     * @return array
     */
    public function process($address, $data)
    {
        $items = $address->getAllItems();
        $promoParentIds = [];

        foreach ($items as $index => $item) {
            if ($item->getAwAfptcIsPromo()) {
                if ($item->getHasChildren()) {
                    $promoParentIds[] = $item->getId();
                }
                unset($items[$index]);
            }
            if (in_array($item->getParentItemId(), $promoParentIds)) {
                unset($items[$index]);
            }
        }

        $data['cached_items_all'] = $items;
        return $data;
    }
}
