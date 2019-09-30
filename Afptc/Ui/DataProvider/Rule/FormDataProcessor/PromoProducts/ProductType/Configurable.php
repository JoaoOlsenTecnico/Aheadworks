<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts\ProductType;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts\ProcessorInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Quote\Api\Data\ProductOptionInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;
use Magento\ConfigurableProduct\Api\Data\ConfigurableItemOptionValueInterface as OptValueInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute;
use Magento\Framework\Api\ExtensibleDataInterface;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;

/**
 * Class Configurable
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts\ProductType
 */
class Configurable implements ProcessorInterface
{
    /**#@+
     * Constants defined for option labels
     */
    const OPTION_ID_LABEL = 'option_id_label';
    const OPTION_VALUE_LABEL = 'option_value_label';
    /**#@-*/

    /**
     * Constants for configurable item option
     */
    const CONFIGURABLE_ITEM_OPTIONS = 'configurable_item_options';
    const DEFAULT_VALUES = 'default_values';

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var ConfigurableType
     */
    private $configurableType;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param ConfigurableType $configurableType
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        ConfigurableType $configurableType
    ) {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->configurableType = $configurableType;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareProductOptions($productData, $promoProduct, $product)
    {
        if (isset($promoProduct[RulePromoProductInterface::OPTION])) {
            $configurableItemOptions = $this->prepareOptions(
                $product,
                $promoProduct[RulePromoProductInterface::OPTION]
            );
            $option = [
                self::CONFIGURABLE_ITEM_OPTIONS => $configurableItemOptions,
                self::DEFAULT_VALUES => $this->prepareDefaultValues($configurableItemOptions)
            ];

            $productData[RulePromoProductInterface::OPTION] = $option;
        }

        return $productData;
    }

    /**
     * Prepare product options
     *
     * @param ProductInterface|Product $product
     * @param ProductOptionInterface $option
     * @return mixed
     */
    private function prepareOptions($product, $option)
    {
        $selectedOptions = [];
        $optionArray = is_array($option) ? $option : $this->dataObjectProcessor->buildOutputDataArray(
            $option,
            ProductOptionInterface::class
        );
        $extAttributesKey = ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY;

        if (isset($optionArray[$extAttributesKey][self::CONFIGURABLE_ITEM_OPTIONS])) {
            $selectedOptions = $optionArray[$extAttributesKey][self::CONFIGURABLE_ITEM_OPTIONS];
            foreach ($selectedOptions as &$selectedOption) {
                $productAttributeOptions = $this->configurableType->getConfigurableAttributesAsArray($product);
                $selectedOption[self::OPTION_ID_LABEL] =
                    $productAttributeOptions[$selectedOption[OptValueInterface::OPTION_ID]][Attribute::KEY_LABEL];
                $options = $productAttributeOptions[$selectedOption[OptValueInterface::OPTION_ID]]['options'];
                $result = array_search(
                    $selectedOption[OptValueInterface::OPTION_VALUE],
                    array_column($options, 'value')
                );
                $selectedOption[self::OPTION_VALUE_LABEL] = $options[$result][Attribute::KEY_LABEL];
            }
        }

        return $selectedOptions;
    }

    /**
     * Prepare default values for selected options
     *
     * @param array $configurableItemOptions
     * @return array
     */
    private function prepareDefaultValues($configurableItemOptions)
    {
        $defaultOptions = [];

        foreach ($configurableItemOptions as $configurableItemOption) {
            $defaultOptions[$configurableItemOption[OptValueInterface::OPTION_ID]] =
                $configurableItemOption[OptValueInterface::OPTION_VALUE];
        }

        return $defaultOptions;
    }
}
