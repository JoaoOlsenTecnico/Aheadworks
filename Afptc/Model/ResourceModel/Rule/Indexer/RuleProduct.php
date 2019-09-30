<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer;

use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Indexer\Table\StrategyInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Indexer\Model\ResourceModel\AbstractResource;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Catalog\Model\Product as ProductModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct\DataProcessor;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResourceModel;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct\RuleProductInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct\DataProcessor\Rule;

/**
 * Class RuleProduct
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer
 */
class RuleProduct extends AbstractResource implements IdentityInterface
{
    /**
     * @var int
     */
    const INSERT_PER_QUERY = 500;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var DataProcessor;
     */
    private $dataProcessor;

    /**
     * @var array
     */
    private $entities = [];

    /**
     * @param Context $context
     * @param StrategyInterface $tableStrategy
     * @param EventManagerInterface $eventManager
     * @param DataProcessor $dataProcessor
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        StrategyInterface $tableStrategy,
        EventManagerInterface $eventManager,
        DataProcessor $dataProcessor,
        $connectionName = null
    ) {
        parent::__construct($context, $tableStrategy, $connectionName);
        $this->dataProcessor = $dataProcessor;
        $this->eventManager = $eventManager;
    }

    /**
     * Define main product post index table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(RuleResourceModel::PRODUCT_TABLE_NAME, RuleProductInterface::RULE_PRODUCT_ID);
    }

    /**
     * Reindex all rule product data
     *
     * @return $this
     * @throws \Exception
     */
    public function reindexAll()
    {
        $this->tableStrategy->setUseIdxTable(true);
        $this->clearTemporaryIndexTable();
        $this->beginTransaction();
        try {
            $oldData = $this->selectFromTable();
            $toInsert = $this->dataProcessor->prepareDataToInsert();

            $this->prepareInsertToTable($toInsert);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
        $this->syncData();
        $this->dispatchCleanCacheByTags(array_merge($oldData, $toInsert));
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function syncData()
    {
        try {
            $this->getConnection()->truncateTable($this->getMainTable());
            $this->insertFromTable(
                $this->getIdxTable(),
                $this->getMainTable(),
                false
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $this;
    }

    /**
     * Reindex product rule data for defined ids
     *
     * @param array|int $ids
     * @return $this
     * @throws \Exception
     * @throws LocalizedException
     */
    public function reindexRows($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $toUpdate = $this->dataProcessor->prepareDataToUpdate($ids);
        $this->beginTransaction();
        try {
            $oldData = [];
            if ($toUpdate[Rule::AFFECTED_RULE_IDS]) {
                $condition = $this->getConnection()->prepareSqlCondition(
                    RuleProductInterface::RULE_ID,
                    ['in' => $toUpdate[Rule::AFFECTED_RULE_IDS]]
                );
                $oldData = $this->selectFromTable($condition);
                $this->removeFromTable(RuleProductInterface::RULE_ID, $toUpdate[Rule::AFFECTED_RULE_IDS]);
            }
            unset($toUpdate[Rule::AFFECTED_RULE_IDS]);
            $this->removeFromTable('product_id', $ids);
            $this->prepareInsertToTable($toUpdate, false);
            $this->commit();
            $this->dispatchCleanCacheByTags(array_merge($oldData, $toUpdate));
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
        return $this;
    }

    /**
     * Dispatch clean_cache_by_tags event
     *
     * @param array $entities
     * @return void
     */
    private function dispatchCleanCacheByTags($entities = [])
    {
        $this->entities = $entities;
        $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $this]);
    }

    /**
     * {@inheritdoc}
     */
    public function clearTemporaryIndexTable()
    {
        $this->getConnection()->truncateTable($this->getIdxTable());
    }

    /**
     * Prepare data and partial insert to index or main table
     *
     * @param $data
     * @param bool $intoIndexTable
     * @return $this
     * @throws LocalizedException
     */
    private function prepareInsertToTable($data, $intoIndexTable = true)
    {
        $counter = 0;
        $toInsert = [];
        foreach ($data as $row) {
            $counter++;
            $toInsert[] = $row;
            if ($counter % self::INSERT_PER_QUERY == 0) {
                $this->insertToTable($toInsert, $intoIndexTable);
                $toInsert = [];
            }
        }
        $this->insertToTable($toInsert, $intoIndexTable);
        return $this;
    }

    /**
     * Insert to index table
     *
     * @param $toInsert
     * @param bool $intoIndexTable
     * @return $this
     * @throws LocalizedException
     */
    private function insertToTable($toInsert, $intoIndexTable = true)
    {
        $table = $intoIndexTable
            ? $this->getTable($this->getIdxTable())
            : $this->getMainTable();
        if (count($toInsert)) {
            $this->getConnection()->insertMultiple(
                $table,
                $toInsert
            );
        }
        return $this;
    }

    /**
     * Remove data from index table
     *
     * @param string $field
     * @param array $data
     * @throws LocalizedException
     */
    private function removeFromTable($field, $data)
    {
        $connection = $this->getConnection();
        $connection->delete(
            $this->getMainTable(),
            [$connection->prepareSqlCondition($field, ['in' => $data])]
        );
    }

    /**
     * Select data from index table
     *
     * @param string $condition
     * @return array
     * @throws LocalizedException
     */
    private function selectFromTable($condition = null)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
        ->from(
            $this->getMainTable(),
            [RuleProductInterface::PRODUCT_ID]
        )->group(RuleProductInterface::PRODUCT_ID);
        if ($condition) {
            $select->where($condition);
        }

        return $connection->fetchAll($select);
    }

    /**
     * Get affected cache tags
     *
     * @return array
     * @codeCoverageIgnore
     */
    public function getIdentities()
    {
        $identities = [];
        foreach ($this->entities as $entity) {
            $identities[] = ProductModel::CACHE_TAG . '_' . $entity[RuleProductInterface::PRODUCT_ID];
        }
        return array_unique($identities);
    }
}
