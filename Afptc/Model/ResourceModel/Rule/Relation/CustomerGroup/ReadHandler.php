<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\CustomerGroup;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResourceModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\AbstractReadHandler;

/**
 * Class ReadHandler
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\CustomerGroup
 */
class ReadHandler extends AbstractReadHandler
{
    /**
     * Read column
     */
    const CUSTOMER_GROUP_ID = 'customer_group_id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->initTable(RuleResourceModel::CUSTOMER_GROUP_TABLE_NAME);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function process($entity, $arguments)
    {
        $customerGroupIds = $this->getCustomerGroupData($entity->getRuleId());
        $entity->setCustomerGroupIds($customerGroupIds);
    }

    /**
     * Retrieve customer group ids
     *
     * @param int $entityId
     * @return array
     * @throws \Exception
     */
    private function getCustomerGroupData($entityId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->tableName, self::CUSTOMER_GROUP_ID)
            ->where(RuleInterface::RULE_ID . ' = :id');
        return $connection->fetchCol($select, ['id' => $entityId]);
    }
}
