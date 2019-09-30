<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Indexer\Product\Save;

use Aheadworks\Afptc\Model\Indexer\Rule\Processor as RuleProcessor;
use Magento\Catalog\Model\Product;

/**
 * Class ApplyRulesAfterReindex
 *
 * @package Aheadworks\Afptc\Plugin\Indexer\Product\Save
 */
class ApplyRulesAfterReindex
{
    /**
     * @var RuleProcessor
     */
    protected $ruleProcessor;

    /**
     * @param RuleProcessor $ruleProcessor
     */
    public function __construct(RuleProcessor $ruleProcessor)
    {
        $this->ruleProcessor = $ruleProcessor;
    }

    /**
     * Apply catalog rules after product resource model save
     *
     * @param Product $subject
     * @return void
     */
    public function afterReindex(Product $subject)
    {
        $this->ruleProcessor->reindexRow($subject->getId());
    }
}
