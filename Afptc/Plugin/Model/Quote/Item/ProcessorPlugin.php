<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\Quote\Item;

use Aheadworks\Afptc\Api\Data\CartItemRuleInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\CartItemExtensionInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory;
use Aheadworks\Afptc\Api\Data\CartItemRuleInterfaceFactory;
use Magento\Quote\Model\Quote\Item;

/**
 * Class ProcessorPlugin
 * @package Aheadworks\Afptc\Plugin\Model\Quote\Item
 */
class ProcessorPlugin
{
    /**
     * @var CartItemExtensionInterfaceFactory
     */
    private $cartItemExtensionFactory;

    /**
     * @var CartItemRuleInterfaceFactory
     */
    private $cartItemRuleFactory;

    /**
     * @param CartItemExtensionInterfaceFactory $cartItemExtensionFactory
     * @param CartItemRuleInterfaceFactory $cartItemRuleFactory
     */
    public function __construct(
        CartItemExtensionInterfaceFactory $cartItemExtensionFactory,
        CartItemRuleInterfaceFactory $cartItemRuleFactory
    ) {
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
        $this->cartItemRuleFactory = $cartItemRuleFactory;
    }

    /**
     * Set promo params to quote item if needed
     *
     * @param Item\Processor $subject
     * @param \Closure $proceed
     * @param Item $item
     * @param DataObject $request
     * @param Product $candidate
     * @return void
     */
    public function aroundPrepare($subject, \Closure $proceed, $item, $request, $candidate)
    {
        $proceed($item, $request, $candidate);
        if ($candidate->getAwAfptcIsPromo()) {
            /** @var CartItemRuleInterface[] $requestRuleOptions */
            $requestRuleOptions = $request->getAwAfptcRules();
            $extensionAttributes = $this->getExtensionAttributes($item);
            /** @var CartItemRuleInterface[] $itemRuleOptions */
            $itemRuleOptions = is_array($extensionAttributes->getAwAfptcRulesRequest())
                ? $extensionAttributes->getAwAfptcRulesRequest()
                : [];

            foreach ($requestRuleOptions as $requestRuleOption) {
                $index = $this->matchOption($itemRuleOptions, $requestRuleOption);
                if ($index !== null) {
                    $itemRuleOption = $itemRuleOptions[$index];
                    $itemRuleOption->setQty($itemRuleOption->getQty() + $requestRuleOption->getQty());
                    $itemRuleOptions[$index] = $itemRuleOption;
                } else {
                    $itemRuleOptions[] = $requestRuleOption;
                }
            }
            $extensionAttributes->setAwAfptcRulesRequest($itemRuleOptions);
            $item->setExtensionAttributes($extensionAttributes);
            $item->setAwAfptcIsPromo($request->getAwAfptcIsPromo());
            $item->setAwUniqueId($request->setAwAfptcUniqueId());
        }
    }

    /**
     * Find equal option for merge qty
     *
     * @param CartItemRuleInterface[] $options
     * @param CartItemRuleInterface $requestRuleOption
     * @return int|null
     */
    private function matchOption($options, $requestRuleOption)
    {
        $matchOpt = null;
        foreach ($options as $index => $option) {
            if ($option->getRuleId() == $requestRuleOption->getRuleId()) {
                $matchOpt = $index;
            }
        }
        return $matchOpt;
    }

    /**
     * Retrieve cart item extension interface
     *
     * @param CartItemInterface $item
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
