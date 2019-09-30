<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option\Configuration;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option\ConfigurationInterface;
use Magento\ConfigurableProduct\Api\Data\ConfigurableItemOptionValueInterface;
use Magento\ConfigurableProduct\Block\Product\View\Type\ConfigurableFactory as ConfigurableBlockFactory;
use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as ConfigurableBlock;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option\Type;

/**
 * Class Configurable
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option\Configuration
 */
class Configurable implements ConfigurationInterface
{
    /**
     * @var Calculator
     */
    private $calculator;

    /**
     * @var ConfigurableBlockFactory
     */
    private $configurableBlockFactory;

    /**
     * @param Calculator $calculator
     * @param ConfigurableBlockFactory $configurableBlockFactory
     */
    public function __construct(
        Calculator $calculator,
        ConfigurableBlockFactory $configurableBlockFactory
    ) {
        $this->calculator = $calculator;
        $this->configurableBlockFactory = $configurableBlockFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions($product, $ruleMetadataPromoProduct = null)
    {
        /** @var ConfigurableBlock $block */
        $block = $this->configurableBlockFactory->create();
        $block->setProduct($product);
        $spConfig = \Zend_Json::decode($block->getJsonConfig());

        $spConfig['defaultValues'] = $ruleMetadataPromoProduct
            ? $this->getDefaultOptionValues($ruleMetadataPromoProduct)
            : [];

        $options = [
            'attributes' => $this->getAttributes($block),
            'spConfig' => $spConfig,
            'gallerySwitchStrategy' =>
                $block->getVar('gallery_switch_strategy', 'Magento_ConfigurableProduct') ?: 'replace'
        ];

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsByMetadata($product, $ruleMetadata, $ruleMetadataPromoProduct)
    {
        $options = $this->getOptions($product, $ruleMetadataPromoProduct);

        $spConfig = $options['spConfig'];
        $options['spConfig'] = $this->modifyPrices($spConfig, $ruleMetadata);

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionType($options)
    {
        if (!empty($options)) {
            $optionType = (count($options['spConfig']['defaultValues']) < count($options['attributes']))
                ? Type::CONFIGURABLE_OPTION
                : Type::PRE_CONFIGURED_OPTION;
        } else {
            $optionType = Type::NO_OPTION;
        }
        return $optionType;
    }

    /**
     * Modify base prices
     *
     * @param array $spConfig
     * @param RuleMetadataInterface $ruleMetadata
     * @return array
     */
    private function modifyPrices($spConfig, $ruleMetadata)
    {
        if (isset($spConfig['optionPrices'])) {
            foreach ($spConfig['optionPrices'] as &$price) {
                if (isset($price['finalPrice']) && isset($price['finalPrice']['amount'])) {
                    $price['finalPrice']['amount'] = $this->calculator
                        ->calculatePrice($price['finalPrice']['amount'], $ruleMetadata);
                }
            }
        }
        if (isset($spConfig['prices']) && isset($spConfig['prices']['finalPrice'])
            && isset($spConfig['prices']['finalPrice']['amount'])
        ) {
            $spConfig['prices']['finalPrice']['amount'] = $this->calculator
                ->calculatePrice($spConfig['prices']['finalPrice']['amount'], $ruleMetadata);
        }
        return $spConfig;
    }

    /**
     * Retrieve default option values
     *
     * @param RuleMetadataPromoProductInterface $ruleMetadataPromoProduct
     * @return array
     */
    private function getDefaultOptionValues($ruleMetadataPromoProduct)
    {
        $defaultValues = [];
        if ($ruleMetadataPromoProduct->getOption()
            && $ruleMetadataPromoProduct->getOption()->getExtensionAttributes()
        ) {
            $extensionAttributes = $ruleMetadataPromoProduct->getOption()->getExtensionAttributes();
            $options = $extensionAttributes->getConfigurableItemOptions() ? : [];
            /** @var ConfigurableItemOptionValueInterface $option */
            foreach ($options as $option) {
                $defaultValues[$option->getOptionId()] = $option->getOptionValue();
            }
        }
        return $defaultValues;
    }

    /**
     * Retrieve configurable attributes
     *
     * @param ConfigurableBlock $block
     * @return array
     */
    private function getAttributes($block)
    {
        $options = [];
        $attributes = $block->decorateArray($block->getAllowAttributes());
        /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute $attribute */
        foreach ($attributes as $attribute) {
            $attributeId = $attribute->getAttributeId();
            $productAttr = $attribute->getProductAttribute();
            $options[$attributeId] = [
                'id' => $attributeId,
                'code' => $productAttr->getAttributeCode(),
                'label' => $productAttr->getStoreLabel()
            ];
        }
        return $options;
    }
}
