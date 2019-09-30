<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Option\Configuration;

use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Option\ConfigurationInterface;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as TypeConfigurable;
use Magento\ConfigurableProduct\Api\Data\ConfigurableItemOptionValueInterface;

/**
 * Class Configurable
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Option\Configuration
 */
class Configurable implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getActiveOptions(ItemInterface $item)
    {
        $product = $item->getProduct();
        /** @var TypeConfigurable $productType */
        $productType = $product->getTypeInstance();
        $defaultValues = $productType->getSelectedAttributesInfo($product);

        $options = [];
        foreach ($defaultValues as $defaultValue) {
            $options[] = [
                ConfigurableItemOptionValueInterface::OPTION_ID => (string)$defaultValue['option_id'],
                ConfigurableItemOptionValueInterface::OPTION_VALUE => (int)$defaultValue['option_value']
            ];
        }

        return ['configurable_item_options' => $options];
    }

    /**
     * {@inheritdoc}
     */
    public function processOptions($optionsData)
    {
        $options = [];
        if (isset($optionsData['super_attribute']) && is_array($optionsData['super_attribute'])) {
            foreach ($optionsData['super_attribute'] as $optionId => $optionValue) {
                $options[] = [
                    ConfigurableItemOptionValueInterface::OPTION_ID => (string)$optionId,
                    ConfigurableItemOptionValueInterface::OPTION_VALUE => (int)$optionValue
                ];
            }
        }
        return ['configurable_item_options' => $options];
    }
}
