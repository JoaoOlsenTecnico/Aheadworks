<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Metadata\Rule;

use Aheadworks\Afptc\Model\Metadata\Rule\Discount\Item;
use Aheadworks\Afptc\Model\Metadata\Rule\Discount\ItemFactory;

/**
 * Class Discount
 *
 * @package Aheadworks\Afptc\Model\Metadata\Rule
 */
class Discount
{
    /**
     * @var float
     */
    private $amount;

    /**
     * @var float
     */
    private $baseAmount;

    /**
     * @var Item[]
     */
    private $items;

    /**
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * @param ItemFactory $itemFactory
     */
    public function __construct(
        ItemFactory $itemFactory
    ) {
        $this->itemFactory = $itemFactory;
        $this
            ->setAmount(0)
            ->setBaseAmount(0)
            ->setItems([]);
    }

    /**
     * Is discount available
     *
     * @return bool
     */
    public function isDiscountAvailable()
    {
        return $this->getAmount() >= 0;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get base amount
     *
     * @return float
     */
    public function getBaseAmount()
    {
        return $this->baseAmount;
    }

    /**
     * Set base amount
     *
     * @param float $baseAmount
     * @return $this
     */
    public function setBaseAmount($baseAmount)
    {
        $this->baseAmount = $baseAmount;
        return $this;
    }

    /**
     * Get items
     *
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set items
     *
     * @param Item[] $items
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Retrieve total qty by all items
     *
     * @param int $ruleId
     * @return float
     */
    public function getTotalQtyByRule($ruleId)
    {
        $totalQty = 0;
        foreach ($this->items as $item) {
            $totalQty += $item->getQtyByRule($ruleId);
        }

        return $totalQty;
    }

    /**
     * Retrieve item discount data by item id
     *
     * @param int $itemId
     * @param bool $createNew
     * @return Item
     */
    public function getItemById($itemId, $createNew = false)
    {
        $item = $this->searchItem($itemId, $this->items);
        if (!$item && $createNew) {
            $item = $this->itemFactory->create();
            $this->items[$itemId] = $item;
        }

        return $item;
    }

    /**
     * Retrieve child item by $itemId and $childItemId
     *
     * @param int $itemId
     * @param int $childItemId
     * @return Item|bool
     */
    public function getChildItemById($itemId, $childItemId)
    {
        $item = $this->getItemById($itemId);

        return $this->searchItem($childItemId, $item->getChildren());
    }

    /**
     * Search item in items array
     *
     * @param int $itemId
     * @param Item[] $items
     * @return Item|bool
     */
    private function searchItem($itemId, $items)
    {
        foreach ($items as $item) {
            if ($item->getItem()->getAwAfptcId() == $itemId) {
                return $item;
            }
        }
        return false;
    }
}
