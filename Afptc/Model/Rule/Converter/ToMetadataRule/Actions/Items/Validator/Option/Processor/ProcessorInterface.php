<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator\Option\Processor;

use Magento\Quote\Api\Data\ProductOptionExtensionInterface;

/**
 * Interface ProcessorInterface
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator\Option\Processor
 */
interface ProcessorInterface
{
    /**
     * Check if options are valid
     *
     * @param ProductOptionExtensionInterface|null $quoteItemOptions
     * @param ProductOptionExtensionInterface|null $promoProductOptions
     * @return bool
     */
    public function isValid($quoteItemOptions, $promoProductOptions);
}
