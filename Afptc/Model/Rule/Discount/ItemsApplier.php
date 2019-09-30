<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount;

use Aheadworks\Afptc\Model\Metadata\Rule\Discount as MetadataRuleDiscount;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory;
use Magento\Quote\Api\Data\CartItemExtensionInterface;

/**
 * Class ItemsApplier
 *
 * @package Aheadworks\Afptc\Model\Rule\Discount
 */
class ItemsApplier
{
    /**
     * @var CartItemExtensionInterfaceFactory
     */
    private $cartItemExtensionFactory;

    /**
     * @param CartItemExtensionInterfaceFactory $cartItemExtensionFactory
     */
    public function __construct(
        CartItemExtensionInterfaceFactory $cartItemExtensionFactory
    ) {
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
    }

    /**
     * Reset AFPTC data in item
     *
     * @param CartItemInterface|AbstractItem $item
     * @return $this
     */
    public function reset($item)
    {
        $extensionAttributes = $this->getExtensionAttributes($item)->setAwAfptcRules([]);

        $item
            ->setAwAfptcAmount(0)
            ->setBaseAwAfptcAmount(0)
            ->setAwAfptcPercent(null)
            ->setAwAfptcRuleIds(null)
            ->setAwAfptcQty(null)
            ->setExtensionAttributes($extensionAttributes);

        $address = $item->getAddress();
        $address->setAwAfptcRuleIds(null);

        if ($item->getHasChildren() && $item->isChildrenCalculated()) {
            foreach ($item->getChildren() as $child) {
                $child
                    ->setAwAfptcAmount(0)
                    ->setBaseAwAfptcAmount(0);
            }
        }

        return $this;
    }

    /**
     * Apply rule discount by items
     *
     * @param CartItemInterface[]|AbstractItem[] $items
     * @param MetadataRuleDiscount $metadataRuleDiscount
     * @return void
     */
    public function apply($items, $metadataRuleDiscount)
    {
        foreach ($items as $item) {
            // To determine the child item discount, we calculate the parent
            if ($item->getParentItem()) {
                continue;
            }

            $this->reset($item)->processApply($item, $metadataRuleDiscount);
        }
    }

    /**
     * Apply rule discount by items
     *
     * @param CartItemInterface|AbstractItem $item
     * @param MetadataRuleDiscount $metadataRuleDiscount
     * @return $this
     */
    private function processApply($item, $metadataRuleDiscount)
    {
        $itemDiscount = $metadataRuleDiscount->getItemById($item->getAwAfptcId());
        if ($itemDiscount) {
            $ruleIds = $itemDiscount->getRuleIdsAsString();

            $extensionAttributes = $this->getExtensionAttributes($item)
                ->setAwAfptcRules($itemDiscount->getCartRules());

            $item
                ->setAwAfptcPercent($itemDiscount->getPercent())
                ->setAwAfptcAmount($itemDiscount->getAmount())
                ->setBaseAwAfptcAmount($itemDiscount->getBaseAmount())
                ->setAwAfptcRuleIds($ruleIds)
                ->setAwAfptcQty($itemDiscount->getQty())
                ->setAwAfptcIsPromo(true)
                ->setExtensionAttributes($extensionAttributes);

            $address = $item->getAddress();
            $address->setAwAfptcRuleIds($ruleIds);

            if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                foreach ($item->getChildren() as $child) {
                    $childDiscount = $metadataRuleDiscount->getChildItemById(
                        $item->getAwAfptcId(),
                        $child->getAwAfptcId()
                    );
                    if ($childDiscount) {
                        $child
                            ->setAwAfptcAmount($childDiscount->getAmount())
                            ->setBaseAwAfptcAmount($childDiscount->getBaseAmount());
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Retrieve cart item extension interface
     *
     * @param CartItemInterface|AbstractItem $item
     * @return CartItemExtensionInterface
     */
    private function getExtensionAttributes($item)
    {
        $extensionAttributes = $item->getExtensionAttributes()
            ? $item->getExtensionAttributes()
            : $this->cartItemExtensionFactory->create();

        return $extensionAttributes;
    }
}
