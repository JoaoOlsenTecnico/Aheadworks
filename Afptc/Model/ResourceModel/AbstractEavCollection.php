<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel;

use Magento\Eav\Model\Entity\Collection\AbstractCollection as EavEntityAbstractCollection;
use Magento\Store\Model\Store;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Class AbstractEavCollection
 *
 * @package Aheadworks\Afptc\Model\ResourceModel
 */
class AbstractEavCollection extends EavEntityAbstractCollection
{
    /**
     * Current scope (store Id)
     *
     * @var int
     */
    protected $storeId;

    /**
     * @var string[]
     */
    private $linkageTableNames = [];

    /**
     * Attach relation table data to collection items
     *
     * @param string $tableName
     * @param string $columnName
     * @param string $linkageColumnName
     * @param string|array $columnNameRelationTable
     * @param string $fieldName
     * @param string $sortOrderField|null
     * @return void
     */
    protected function attachRelationTable(
        $tableName,
        $columnName,
        $linkageColumnName,
        $columnNameRelationTable,
        $fieldName,
        $sortOrderField = null
    ) {
        $ids = $this->getColumnValues($columnName);
        if (count($ids)) {
            $connection = $this->getConnection();
            $select = $connection->select()
                ->from([$tableName . '_table' => $this->getTable($tableName)])
                ->where($tableName . '_table.' . $linkageColumnName . ' IN (?)', $ids);
            if ($sortOrderField) {
                $select->order($sortOrderField . ' ASC');
            }
            $result = $connection->fetchAll($select);

            /** @var \Magento\Framework\DataObject $item */
            foreach ($this as $item) {
                $resultIds = [];
                $id = $item->getData($columnName);
                foreach ($result as $data) {
                    if ($data[$linkageColumnName] == $id) {
                        if (is_array($columnNameRelationTable)) {
                            $fieldValue = [];
                            foreach ($columnNameRelationTable as $columnNameRelation) {
                                $fieldValue[$columnNameRelation] = $data[$columnNameRelation];
                            }
                            $resultIds[] = $fieldValue;
                        } else {
                            $resultIds[] = $data[$columnNameRelationTable];
                        }
                    }
                }
                $item->setData($fieldName, $resultIds);
            }
        }
    }

    /**
     * Join to linkage table if filter is applied
     *
     * @param string $tableName
     * @param string $columnName
     * @param string $linkageColumnName
     * @param string $columnFilter
     * @param string $fieldName
     * @return $this
     */
    protected function joinLinkageTable(
        $tableName,
        $columnName,
        $linkageColumnName,
        $columnFilter,
        $fieldName
    ) {
        if ($this->getFilter($columnFilter)) {
            $linkageTableName = $this->generateLinkageTableName($columnFilter);
            if (in_array($linkageTableName, $this->linkageTableNames)) {
                $this->addFilterToMap($columnFilter, $linkageTableName . '.' . $fieldName);
                return $this;
            }

            $this->linkageTableNames[] = $linkageTableName;
            $select = $this->getSelect();
            $select->joinLeft(
                [$linkageTableName => $this->getTable($tableName)],
                'e.' . $columnName . ' = ' . $linkageTableName . '.' . $linkageColumnName,
                []
            );

            $this->addFilterToMap($columnFilter, $linkageTableName . '.' . $fieldName);
        }

        return $this;
    }

    /**
     * Generate linkage table name for join linkage table
     *
     * @param string $field
     * @return string
     */
    protected function generateLinkageTableName($field)
    {
        return $field . '_table';
    }

    /**
     * Set store scope ID
     *
     * @param int|string|StoreInterface $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        if ($storeId instanceof StoreInterface) {
            $storeId = $storeId->getId();
        }
        $this->storeId = (int) $storeId;
        return $this;
    }

    /**
     * Return store id
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * Retrieve attributes load select
     *
     * @param string $table
     * @param array $attributeIds
     * @return \Magento\Framework\DB\Select
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getLoadAttributesSelect($table, $attributeIds = [])
    {
        if (empty($attributeIds)) {
            $attributeIds = $this->_selectAttributes;
        }
        $storeId = $this->getStoreId();
        $connection = $this->getConnection();

        $entityTable = $this->getEntity()->getEntityTable();
        $indexList = $connection->getIndexList($entityTable);
        $entityIdField = $indexList[$connection->getPrimaryKeyName($entityTable)]['COLUMNS_LIST'][0];

        $select = $connection->select()->from(
            ['t_d' => $table],
            ['attribute_id']
        )->join(
            ['e' => $entityTable],
            "e.{$entityIdField} = t_d.{$entityIdField}",
            ['e.rule_id']
        )->where(
            "e.rule_id IN (?)",
            array_keys($this->_itemsById)
        );

        if ($storeId) {
            $joinCondition = [
                't_s.attribute_id = t_d.attribute_id',
                "t_s.{$entityIdField} = t_d.{$entityIdField}",
                $connection->quoteInto('t_s.store_id = ?', $storeId),
            ];
            $select->where(
                't_d.attribute_id IN (?)',
                $attributeIds
            )->joinLeft(
                ['t_s' => $table],
                implode(' AND ', $joinCondition),
                []
            )->where(
                't_d.store_id = ?',
                $connection->getIfNullSql('t_s.store_id', Store::DEFAULT_STORE_ID)
            );
        } else {
            $select->where(
                'attribute_id IN (?)',
                $attributeIds
            )->where(
                'store_id = ?',
                Store::DEFAULT_STORE_ID
            );
        }
        return $select;
    }
}
