<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item;

use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Processor
 *
 * @package Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item
 */
class Processor
{
    /**
     * Retrieve total item price
     *
     * @param AbstractItem $item
     * @param int|null $qty
     * @return float
     */
    public function getTotalItemPrice($item, $qty = null)
    {
        $qty = $qty ? : $item->getTotalQty();
        // Calculate parent price with discount for bundle dynamic product
        if ($item->getHasChildren() && $item->isChildrenCalculated()) {
            $rowTotal = $this->getItemPrice($item) * $qty;
            foreach ($item->getChildren() as $child) {
                $rowTotal = $rowTotal
                    - $child->getDiscountAmount()
                    - $item->getAwRewardPointsAmount()
                    - $item->getAwRafAmount();
            }
        } else {
            $rowTotal = $this->getItemPrice($item) * $qty
                - $item->getDiscountAmount()
                - $item->getAwRewardPointsAmount()
                - $item->getAwRafAmount();
        }

        return $rowTotal;
    }

    /**
     * Retrieve total item base price
     *
     * @param AbstractItem $item
     * @param int|null $qty
     * @return float
     */
    public function getTotalItemBasePrice($item, $qty = null)
    {
        $qty = $qty ? : $item->getTotalQty();
        // Calculate parent price with discount for bundle dynamic product
        if ($item->getHasChildren() && $item->isChildrenCalculated()) {
            $baseRowTotal = $this->getItemBasePrice($item) * $qty;
            foreach ($item->getChildren() as $child) {
                $baseRowTotal = $baseRowTotal
                    - $child->getBaseDiscountAmount()
                    - $item->getBaseAwRewardPointsAmount()
                    - $item->getBaseAwRafAmount();
            }
        } else {
            $baseRowTotal = $this->getItemBasePrice($item) * $qty
                - $item->getBaseDiscountAmount()
                - $item->getBaseAwRewardPointsAmount()
                - $item->getBaseAwRafAmount();
        }

        return $baseRowTotal;
    }

    /**
     * Retrieve item price
     *
     * @param AbstractItem $item
     * @return float
     */
    private function getItemPrice($item)
    {
        $price = $item->getDiscountCalculationPrice();
        $calcPrice = $item->getCalculationPrice();

        return $price === null ? $calcPrice : $price;
    }

    /**
     * Retrieve item base price
     *
     * @param AbstractItem $item
     * @return float
     */
    private function getItemBasePrice($item)
    {
        $price = $item->getDiscountCalculationPrice();

        return $price !== null ? $item->getBaseDiscountCalculationPrice() : $item->getBaseCalculationPrice();
    }
}
