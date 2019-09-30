<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Cart\Item\ExtensionAttributes\Processor;

use Aheadworks\Afptc\Api\Data\CartItemRuleInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory;
use Magento\Quote\Api\Data\CartItemExtensionInterface;
use Aheadworks\Afptc\Api\Data\CartItemRuleInterfaceFactory;
use Magento\Quote\Model\Quote\Item\Option;
use Aheadworks\Afptc\Api\Data\CartItemInterface as AfptcCartItemInterface;

/**
 * Class RuleConfig
 *
 * @package Aheadworks\Afptc\Model\Cart\Item\ExtensionAttributes\Processor
 */
class RuleConfig implements ProcessorInterface
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
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param CartItemExtensionInterfaceFactory $cartItemExtensionFactory
     * @param CartItemRuleInterfaceFactory $cartItemRuleFactory
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param Json $serializer
     */
    public function __construct(
        CartItemExtensionInterfaceFactory $cartItemExtensionFactory,
        CartItemRuleInterfaceFactory $cartItemRuleFactory,
        DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        Json $serializer
    ) {
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
        $this->cartItemRuleFactory = $cartItemRuleFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataBeforeSave($item)
    {
        if ($item->getExtensionAttributes() && $item->getExtensionAttributes()->getAwAfptcRules()) {
            $cartRules = [];
            foreach ($item->getExtensionAttributes()->getAwAfptcRules() as $cartRule) {
                $cartRules[] = $this->dataObjectProcessor->buildOutputDataArray(
                    $cartRule,
                    CartItemRuleInterface::class
                );
            }
            $cartRulesSerialized = $this->serializer->serialize($cartRules);
            $item->setAwAfptcRules($cartRulesSerialized);
            $item->setAwAfptcRulesRequest($cartRulesSerialized);
            $buyRequestOption = $item->getOptionByCode('info_buyRequest');
            $buyRequestOption = $this->prepareBuyRequestOption($buyRequestOption, $cartRules);
            $item->addOption($buyRequestOption);
        } else {
            $item->setAwAfptcRules(null);
            $item->setAwAfptcRulesRequest(null);
        }
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataAfterLoad($item)
    {
        if (!$item->getExtensionAttributes() ||
            ($item->getExtensionAttributes() && null === $item->getExtensionAttributes()->getAwAfptcRules())
        ) {
            $cartRuleObjects = $this->convertCartRulesToObject($item->getAwAfptcRules());
            $extensionAttributes = $this->getExtensionAttributes($item)->setAwAfptcRules($cartRuleObjects);
            $item->setExtensionAttributes($extensionAttributes);
        }

        if (!$item->getExtensionAttributes() ||
            ($item->getExtensionAttributes() && null === $item->getExtensionAttributes()->getAwAfptcRulesRequest())
        ) {
            $cartRuleObjects = $this->convertCartRulesToObject($item->getAwAfptcRulesRequest());
            $extensionAttributes = $this->getExtensionAttributes($item)->setAwAfptcRulesRequest($cartRuleObjects);
            $item->setExtensionAttributes($extensionAttributes);
        }

        return $item;
    }

    /**
     * Convert cart rules array to object
     *
     * @param string $cartRulesSerialized
     * @return CartItemRuleInterface[]
     */
    private function convertCartRulesToObject($cartRulesSerialized)
    {
        try {
            $cartRules = $this->serializer->unserialize($cartRulesSerialized);
        } catch (\InvalidArgumentException $e) {
            $cartRules = [];
        }

        $cartRuleObjects = [];
        foreach ($cartRules as $cartRule) {
            /** @var CartItemRuleInterface $cartRuleObject */
            $cartRuleObject = $this->cartItemRuleFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $cartRuleObject,
                $cartRule,
                CartItemRuleInterface::class
            );
            $cartRuleObjects[] = $cartRuleObject;
        }
        return $cartRuleObjects;
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

    /**
     * Prepare buy request option
     *
     * @param Option $buyRequestOption
     * @param array $cartRules
     * @return Option
     */
    private function prepareBuyRequestOption($buyRequestOption, $cartRules)
    {
        $buyRequestData = $this->serializer->unserialize($buyRequestOption->getValue());
        $buyRequestData[AfptcCartItemInterface::AW_AFPTC_RULES] = $cartRules;
        $buyRequestOption->setValue($this->serializer->serialize($buyRequestData));

        return $buyRequestOption;
    }
}
