<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\CustomerGroup;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResourceModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\AbstractSaveHandler;
use Aheadworks\Afptc\Model\Source\Customer\Group;

/**
 * Class SaveHandler
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\CustomerGroup
 */
class SaveHandler extends AbstractSaveHandler
{
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
    public function process($entity, $arguments = [])
    {
        $this->deleteOldChildEntityById($entity->getRuleId());
        $toInsert = $this->getCustomerGroupIds($entity);
        $this->insertChildEntity($toInsert);

        return $entity;
    }

    /**
     * Retrieve array of customer group data to insert
     *
     * @param RuleInterface $entity
     * @return array
     */
    private function getCustomerGroupIds($entity)
    {
        $customerGroupIds = [];
        foreach ($entity->getCustomerGroupIds() as $customerGroupId) {
            $customerGroupIds[] = [
                RuleInterface::RULE_ID => $entity->getRuleId(),
                ReadHandler::CUSTOMER_GROUP_ID => $customerGroupId
            ];
        }
        return $customerGroupIds;
    }
}
