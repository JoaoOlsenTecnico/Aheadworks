<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Attribute;

use Magento\Framework\EntityManager\MetadataPool;
use Magento\Eav\Api\AttributeRepositoryInterface as AttributeRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Store\Model\Store;
use Magento\Framework\App\ResourceConnection;
use Aheadworks\Afptc\Model\Rule;
use Magento\Framework\DB\Sql\UnionExpression;
use Magento\Framework\DB\Select;
use Magento\Framework\Api\AttributeInterface;

/**
 * Class ScopeOverriddenValue
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Attribute
 */
class ScopeOverriddenValue
{
    /**
     * @var AttributeRepository
     */
    private $attributeRepository;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var array
     */
    private $attributesValues;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * ScopeOverriddenValue constructor.
     * @param AttributeRepository $attributeRepository
     * @param MetadataPool $metadataPool
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        MetadataPool $metadataPool,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ResourceConnection $resourceConnection
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->metadataPool = $metadataPool;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Whether attribute value is overridden in specific store
     *
     * @param string $entityType
     * @param Rule $entity
     * @param string $attributeCode
     * @param int|string $storeId
     * @return bool
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function containsValue($entityType, $entity, $attributeCode, $storeId)
    {
        if ((int)$storeId === Store::DEFAULT_STORE_ID) {
            return false;
        }
        if ($this->attributesValues === null) {
            $this->initAttributeValues($entityType, $entity, (int)$storeId);
        }

        return isset($this->attributesValues[$storeId])
            && array_key_exists($attributeCode, $this->attributesValues[$storeId]);
    }

    /**
     * Get attribute default values
     *
     * @param string $entityType
     * @param Rule $entity
     * @return array
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getDefaultValues($entityType, $entity)
    {
        if ($this->attributesValues === null) {
            $this->initAttributeValues($entityType, $entity, (int)$entity->getStoreId());
        }

        return isset($this->attributesValues[Store::DEFAULT_STORE_ID])
            ? $this->attributesValues[Store::DEFAULT_STORE_ID]
            : [];
    }

    /**
     * Initialize attribute values
     *
     * @param string $entityType
     * @param Rule $entity
     * @param int $storeId
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function initAttributeValues($entityType, $entity, $storeId)
    {
        $metadata = $this->metadataPool->getMetadata($entityType);
        /** @var \Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute */
        $attributeTables = [];
        if ($metadata->getEavEntityType()) {
            foreach ($this->getAttributes($entityType) as $attribute) {
                if (!$attribute->isStatic()) {
                    $attributeTables[$attribute->getBackend()->getTable()][] = $attribute->getAttributeId();
                }
            }
            $storeIds = [Store::DEFAULT_STORE_ID];
            if ($storeId !== Store::DEFAULT_STORE_ID) {
                $storeIds[] = $storeId;
            }
            $selects = [];
            $connection = $this->resourceConnection->getConnection();
            foreach ($attributeTables as $attributeTable => $attributeCodes) {
                $select = $connection->select()
                    ->from(
                        ['t' => $attributeTable],
                        [AttributeInterface::VALUE => 't.value', Rule::STORE_ID => 't.store_id']
                    )
                    ->join(
                        ['a' => $this->resourceConnection->getTableName('eav_attribute')],
                        'a.attribute_id = t.attribute_id',
                        [AttributeInterface::ATTRIBUTE_CODE => 'a.attribute_code']
                    )
                    ->where($metadata->getLinkField() . ' = ?', $entity->getData($metadata->getLinkField()))
                    ->where('t.attribute_id IN (?)', $attributeCodes)
                    ->where('t.store_id IN (?)', $storeIds);
                $selects[] = $select;
            }

            $unionSelect = new UnionExpression($selects, Select::SQL_UNION_ALL);
            $attributes = $connection->fetchAll((string)$unionSelect);
            foreach ($attributes as $attribute) {
                $this->attributesValues[$attribute[Rule::STORE_ID]][$attribute[AttributeInterface::ATTRIBUTE_CODE]]
                    = $attribute[AttributeInterface::VALUE];
            }
        }
    }

    /**
     * Get all attributes for entity type
     *
     * @param string $entityType
     * @return \Magento\Eav\Api\Data\AttributeInterface[]
     * @throws \Exception
     */
    private function getAttributes($entityType)
    {
        $metadata = $this->metadataPool->getMetadata($entityType);
        $searchResult = $this->attributeRepository->getList(
            $metadata->getEavEntityType(),
            $this->searchCriteriaBuilder->create()
        );
        return $searchResult->getItems();
    }
}
