<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Indexer\Stock;

use Aheadworks\Afptc\Model\Indexer\Rule\Processor as RuleProcessor;
use Magento\CatalogInventory\Model\Indexer\Stock\Action\Row as RowAction;

/**
 * Class UpdateRowPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Indexer\Stock
 */
class UpdateRowPlugin
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
     * Reindex on sale rule index after update of catalog inventory index
     *
     * @param RowAction $subject
     * @param \Closure $proceed
     * @param $id
     */
    public function aroundExecute(
        $subject,
        \Closure $proceed,
        $id
    ) {
        $proceed($id);
        $this->ruleProcessor->reindexRow($id);
    }
}
