<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator\Option;

use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator\Option\Processor\ProcessorInterface;
use Magento\Quote\Api\Data\ProductOptionExtensionInterface;

/**
 * Class Processor
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator\Option
 */
class Processor
{
    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * @param array $processors
     */
    public function __construct(array $processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * Check if product options is valid
     *
     * @param string $productType
     * @param ProductOptionExtensionInterface|null $quoteItemOptions
     * @param ProductOptionExtensionInterface|null $promoProductOptions
     * @return bool
     */
    public function isValid($productType, $quoteItemOptions, $promoProductOptions)
    {
        return (isset($this->processors[$productType]))
            ? $this->processors[$productType]->isValid($quoteItemOptions, $promoProductOptions)
            : true;
    }
}
