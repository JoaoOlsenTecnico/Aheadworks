<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Indexer\Rule;

use Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct as ResourceRuleProductIndexer;

/**
 * Class AbstractAction
 *
 * @package Aheadworks\Afptc\Model\Indexer\Product
 */
abstract class AbstractAction
{
    /**
     * @var ResourceRuleProductIndexer
     */
    protected $resourceRuleProductIndexer;

    /**
     * @param ResourceRuleProductIndexer $resourceRuleProductIndexer
     */
    public function __construct(
        ResourceRuleProductIndexer $resourceRuleProductIndexer
    ) {
        $this->resourceRuleProductIndexer = $resourceRuleProductIndexer;
    }

    /**
     * Execute action for given ids
     *
     * @param array|int $ids
     * @return void
     */
    abstract public function execute($ids);
}
