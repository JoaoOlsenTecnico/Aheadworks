<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\ProductAttribute;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\EntityManager\MetadataPool;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResourceModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\AbstractSaveHandler;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\ProductAttribute\SaveHandler\ProductAttributeList;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\Website\ReadHandler as WebsiteReadHandler;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\CustomerGroup\ReadHandler as CustomerReadHandler;
use Magento\Eav\Api\Data\AttributeInterface;

/**
 * Class SaveHandler
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\ProductAttribute
 */
class SaveHandler extends AbstractSaveHandler
{
    /**
     * @var ProductAttributeList
     */
    private $productAttributeList;

    /**
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     * @param ProductAttributeList $productAttributeList
     */
    public function __construct(
        MetadataPool $metadataPool,
        ResourceConnection $resourceConnection,
        ProductAttributeList $productAttributeList
    ) {
        parent::__construct($metadataPool, $resourceConnection);
        $this->productAttributeList = $productAttributeList;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->initTable(RuleResourceModel::PRODUCT_ATTRIBUTE_TABLE_NAME);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function process($entity, $arguments = [])
    {
        $this->deleteOldChildEntityById($entity->getRuleId());
        $toInsert = $this->getProductAttributeData($entity);
        $this->insertChildEntity($toInsert);

        return $entity;
    }

    /**
     * Prepare array of product attribute data to insert
     *
     * @param RuleInterface $entity
     * @return array
     * @throws \Exception
     */
    private function getProductAttributeData($entity)
    {
        $data = [];
        $attributeCodes = $this->productAttributeList->prepare($entity);
        $attributeIds = $this->getAttributeIds($attributeCodes);
        if ($attributeIds) {
            foreach ($entity->getCustomerGroupIds() as $customerGroupId) {
                foreach ($entity->getWebsiteIds() as $websiteId) {
                    foreach ($attributeIds as $attribute) {
                        $data[] = [
                            RuleInterface::RULE_ID => $entity->getRuleId(),
                            WebsiteReadHandler::WEBSITE_ID => $websiteId,
                            CustomerReadHandler::CUSTOMER_GROUP_ID => $customerGroupId,
                            AttributeInterface::ATTRIBUTE_ID => $attribute,
                        ];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Get attribute Ids
     *
     * @param array $attributeCodes
     * @return array
     * @throws \Exception
     */
    private function getAttributeIds($attributeCodes)
    {
        $attributeIds = [];
        if (!empty($attributeCodes)) {
            $select = $this->getConnection()->select()->from(
                ['a' => $this->resourceConnection->getTableName('eav_attribute')],
                ['a.' . AttributeInterface::ATTRIBUTE_ID]
            )->where(
                'a.' . AttributeInterface::ATTRIBUTE_CODE . ' IN (?)',
                [$attributeCodes]
            );
            $attributes = $this->getConnection()->fetchAll($select);
            foreach ($attributes as $attribute) {
                $attributeIds[] = $attribute['attribute_id'];
            }
        }

        return $attributeIds;
    }
}
