<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class ProcessorInterface
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor
 */
interface ProcessorInterface
{
    /**
     * Prepare rule data to metadata rule
     *
     * @param RuleMetadataInterface $ruleMetadata
     * @param AbstractItem[] $items
     * @param RuleMetadataInterface[]
     * @return void
     * @throws \Exception
     */
    public function prepareData($ruleMetadata, $items, $metadataRules);
}
