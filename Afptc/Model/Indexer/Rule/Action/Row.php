<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Indexer\Rule\Action;

use Aheadworks\Afptc\Model\Indexer\Rule\AbstractAction;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Row
 *
 * @package Aheadworks\Afptc\Model\Indexer\Rule\Action
 */
class Row extends AbstractAction
{
    /**
     * Execute Row reindex
     *
     * @param int|null $id
     * @return void
     * @throws InputException
     * @throws LocalizedException
     */
    public function execute($id = null)
    {
        if (!isset($id) || empty($id)) {
            throw new InputException(
                __('We can\'t rebuild the index for an undefined entity.')
            );
        }
        try {
            $this->resourceRuleProductIndexer->reindexRows([$id]);
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }
}
