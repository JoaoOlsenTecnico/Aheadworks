<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\Website;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResourceModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\AbstractSaveHandler;

/**
 * Class SaveHandler
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\Website
 */
class SaveHandler extends AbstractSaveHandler
{
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
    public function process($entity, $arguments = [])
    {
        $this->deleteOldChildEntityById($entity->getRuleId());
        $toInsert = $this->getWebsiteIds($entity);
        $this->insertChildEntity($toInsert);

        return $entity;
    }

    /**
     * Retrieve array of website data to insert
     *
     * @param RuleInterface $entity
     * @return array
     */
    private function getWebsiteIds($entity)
    {
        $websiteIds = [];
        foreach ($entity->getWebsiteIds() as $websiteId) {
            $websiteIds[] = [
                RuleInterface::RULE_ID => $entity->getRuleId(),
                ReadHandler::WEBSITE_ID => $websiteId
            ];
        }
        return $websiteIds;
    }
}
