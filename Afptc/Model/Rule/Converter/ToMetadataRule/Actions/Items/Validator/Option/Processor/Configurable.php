<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator\Option\Processor;

use Magento\ConfigurableProduct\Api\Data\ConfigurableItemOptionValueInterface;

/**
 * Class Configurable
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator\Option\Processor
 */
class Configurable implements ProcessorInterface
{
    /**
     * @inheritdoc
     */
    public function isValid($quoteItemOptions, $promoProductOptions)
    {
        $isValid = true;
        $confQuoteItemOptions = $quoteItemOptions->getConfigurableItemOptions() ? : [];
        $confPromoProductOptions = $promoProductOptions->getConfigurableItemOptions() ? : [];

        /** @var ConfigurableItemOptionValueInterface $confPromoProductOption */
        foreach ($confPromoProductOptions as $confPromoProductOption) {
            if (!$this->isValidOption($confPromoProductOption, $confQuoteItemOptions)) {
                $isValid = false;
                break;
            }
        }

        return $isValid;
    }

    /**
     * Is option valid
     *
     * @param ConfigurableItemOptionValueInterface $promoProductOption
     * @param ConfigurableItemOptionValueInterface[] $quoteItemOptions
     * @return bool
     */
    private function isValidOption($promoProductOption, $quoteItemOptions)
    {
        $result = false;
        foreach ($quoteItemOptions as $quoteItemOption) {
            if ($promoProductOption->getOptionId() == $quoteItemOption->getOptionId()
                && $promoProductOption->getOptionValue() == $quoteItemOption->getOptionValue()
            ) {
                $result = true;
                break;
            }
        }
        return $result;
    }
}
