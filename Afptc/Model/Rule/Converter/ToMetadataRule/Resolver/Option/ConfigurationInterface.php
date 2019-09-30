<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Option;

use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;

/**
 * Interface ConfigurationInterface
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Option
 */
interface ConfigurationInterface
{
    /**
     * Get active options array
     *
     * @param ItemInterface $item
     * @return array
     */
    public function getActiveOptions(ItemInterface $item);

    /**
     * Process item options
     *
     * @param array $optionsData
     * @return array
     */
    public function processOptions($optionsData);
}
