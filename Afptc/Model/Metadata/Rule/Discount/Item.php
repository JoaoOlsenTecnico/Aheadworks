<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Metadata\Rule\Discount;

use Aheadworks\Afptc\Api\Data\CartItemRuleInterface;
use Aheadworks\Afptc\Api\Data\CartItemRuleInterfaceFactory;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Item
 *
 * @package Aheadworks\Afptc\Model\Metadata\Rule\Discount
 */
class Item
{
    /**
     * @var AbstractItem
     */
    private $item;

    /**
     * @var array Format: array('ruleId' => '<qty>', ...)
     */
    private $qtyByRule;

    /**
     * @var float[]
     */
    private $percents;

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
    private $children;

    /**
     * @var CartItemRuleInterfaceFactory
     */
    private $cartItemRuleFactory;

    /**
     * @param CartItemRuleInterfaceFactory $cartItemRuleFactory
     */
    public function __construct(
        CartItemRuleInterfaceFactory $cartItemRuleFactory
    ) {
        $this->cartItemRuleFactory = $cartItemRuleFactory;
        $this
            ->setPercents([])
            ->setAmount(0)
            ->setBaseAmount(0)
            ->setQtyByRule([])
            ->setChildren([]);
    }

    /**
     * Set item
     *
     * @return AbstractItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Get item
     *
     * @param AbstractItem $item
     * @return $this
     */
    public function setItem($item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * Get rule ids
     *
     * @return int[]
     */
    public function getRuleIds()
    {
        $ruleIds = [];
        foreach ($this->qtyByRule as $ruleId => $qty) {
            $ruleIds[] = $ruleId;
        }
        return $ruleIds;
    }

    /**
     * Get rule ids as string
     *
     * @return string
     */
    public function getRuleIdsAsString()
    {
        return implode(',', $this->getRuleIds());
    }

    /**
     * Get percents
     *
     * @return float[]
     */
    public function getPercents()
    {
        return $this->percents;
    }

    /**
     * Set percents
     *
     * @param float[] $percents
     * @return $this
     */
    public function setPercents($percents)
    {
        $this->percents = $percents;
        return $this;
    }

    /**
     * Add percent
     *
     * @param float $percent
     * @param int $qty
     * @return $this
     */
    public function addPercent($percent, $qty)
    {
        for ($i = 0; $i < $qty; $i++) {
            $this->percents[] = $percent;
        }
        return $this;
    }

    /**
     * Get percent
     *
     * @return float
     */
    public function getPercent()
    {
        $percents = 0;
        foreach ($this->percents as $percent) {
            $percents += $percent;
        }

        return min(100, ($percents / $this->getQty()));
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
     * Add amount
     *
     * @param float $amount
     * @return $this
     */
    public function addAmount($amount)
    {
        $this->amount += $amount;
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
     * Add base amount
     *
     * @param float $baseAmount
     * @return $this
     */
    public function addBaseAmount($baseAmount)
    {
        $this->baseAmount += $baseAmount;
        return $this;
    }

    /**
     * Get qty
     *
     * @return float
     */
    public function getQty()
    {
        $sumQty = 0;
        foreach ($this->qtyByRule as $qty) {
            $sumQty += $qty;
        }
        return $sumQty;
    }

    /**
     * Get cart rules
     *
     * @return CartItemRuleInterface[]
     */
    public function getCartRules()
    {
        $cartRules = [];
        foreach ($this->qtyByRule as $ruleId => $qty) {
            /** @var CartItemRuleInterface $cartItemRule */
            $cartItemRule = $this->cartItemRuleFactory->create();
            $cartItemRule
                ->setRuleId($ruleId)
                ->setQty($qty);
            $cartRules[] = $cartItemRule;
        }
        return $cartRules;
    }

    /**
     * Get qty by rule
     *
     * @param int $ruleId
     * @return float
     */
    public function getQtyByRule($ruleId)
    {
        $qty = 0;
        if (isset($this->qtyByRule[$ruleId])) {
            $qty = $this->qtyByRule[$ruleId];
        }
        return $qty;
    }

    /**
     * Set qty by rule
     *
     * @param array $qtyByRule
     * @return $this
     */
    public function setQtyByRule($qtyByRule)
    {
        $this->qtyByRule = $qtyByRule;
        return $this;
    }

    /**
     * Add qty by rule
     *
     * @param int $ruleId
     * @param float $qty
     * @return $this
     */
    public function addQtyByRule($qty, $ruleId)
    {
        if (isset($this->qtyByRule[$ruleId])) {
            $this->qtyByRule[$ruleId] += $qty;
        } else {
            $this->qtyByRule[$ruleId] = $qty;
        }

        return $this;
    }

    /**
     * Get children
     *
     * @return Item[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set children
     *
     * @param Item[] $children
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }
}
