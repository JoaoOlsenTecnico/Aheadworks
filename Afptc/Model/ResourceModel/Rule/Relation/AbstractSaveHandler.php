<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Relation;

use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class AbstractSaveHandler
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Relation
 */
abstract class AbstractSaveHandler extends AbstractHandler
{
    /**
     * Remove old child entity by id
     *
     * @param int $id
     * @return int
     * @throws \Exception
     */
    protected function deleteOldChildEntityById($id)
    {
        return $this->getConnection()->delete($this->tableName, [RuleInterface::RULE_ID . ' = ?' => $id]);
    }

    /**
     * Insert child entity
     *
     * @param array $toInsert
     * @return $this
     * @throws \Exception
     */
    protected function insertChildEntity($toInsert)
    {
        if (!empty($toInsert)) {
            $this->getConnection()->insertMultiple($this->tableName, $toInsert);
        }
        return $this;
    }
}
