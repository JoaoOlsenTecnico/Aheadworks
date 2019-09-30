<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator;

use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Option as OptionResolver;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator\Option\Processor;

/**
 * Class Option
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator
 */
class Option
{
    /**
     * @var OptionResolver
     */
    private $optionResolver;

    /**
     * @var Processor
     */
    private $optionProcessor;

    /**
     * @param OptionResolver $optionResolver
     * @param Processor $optionProcessor
     */
    public function __construct(
        OptionResolver $optionResolver,
        Processor $optionProcessor
    ) {
        $this->optionResolver = $optionResolver;
        $this->optionProcessor = $optionProcessor;
    }

    /**
     * Check if valid quote item option
     *
     * @param AbstractItem $item
     * @param RulePromoProductInterface $promoProduct
     * @return bool
     */
    public function isValid($item, $promoProduct)
    {
        $isValid = true;
        if ($promoProduct->getOption()
            && $promoProduct->getOption()->getExtensionAttributes()
        ) {
            $quoteItemOptions = $this->optionResolver->resolveActiveOptions($item)->getExtensionAttributes();
            $promoProductOptions = $promoProduct->getOption()->getExtensionAttributes();

            $isValid = $this->optionProcessor->isValid(
                $item->getProductType(),
                $quoteItemOptions,
                $promoProductOptions
            );
        }

        return $isValid;
    }
}
