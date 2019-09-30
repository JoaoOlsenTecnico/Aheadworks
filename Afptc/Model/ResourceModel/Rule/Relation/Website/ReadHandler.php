<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\Website;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResourceModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\AbstractReadHandler;

/**
 * Class ReadHandler
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\Website
 */
class ReadHandler extends AbstractReadHandler
{
    /**
     * Read column
     */
    const WEBSITE_ID = 'website_id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->initTable(RuleResourceModel::WEBSITE_TABLE_NAME);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function process($entity, $arguments)
    {
        $websiteIds = $this->getWebsiteData($entity->getRuleId());
        $entity->setWebsiteIds($websiteIds);
    }

    /**
     * Retrieve website ids
     *
     * @param int $entityId
     * @return array
     * @throws \Exception
     */
    private function getWebsiteData($entityId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->tableName, self::WEBSITE_ID)
            ->where(RuleInterface::RULE_ID . ' = :id');
        return $connection->fetchCol($select, ['id' => $entityId]);
    }
}
