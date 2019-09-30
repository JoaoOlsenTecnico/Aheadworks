<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Relation;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class AbstractHandler
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Relation
 */
abstract class AbstractHandler implements ExtensionInterface
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        MetadataPool $metadataPool,
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->metadataPool = $metadataPool;
        /**
         * Please override this one instead of overriding real __construct constructor
         */
        $this->_construct();
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        if ($this->isValid($entity)) {
            $this->process($entity, $arguments);
        }

        return $entity;
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    abstract protected function _construct();

    /**
     * Process object
     *
     * @param RuleInterface $entity
     * @param array $arguments
     * @return $this
     */
    abstract protected function process($entity, $arguments);

    /**
     * Check if valid entity
     *
     * @param RuleInterface $entity
     * @return bool
     */
    protected function isValid($entity)
    {
        return !empty($entity->getRuleId());
    }

    /**
     * Initialize table
     *
     * @param string $tableName
     * @return $this
     */
    protected function initTable($tableName)
    {
        $this->tableName = $this->resourceConnection->getTableName($tableName);
        return $this;
    }

    /**
     * Get connection
     *
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     * @throws \Exception
     */
    protected function getConnection()
    {
        return $this->resourceConnection->getConnectionByName(
            $this->metadataPool->getMetadata(RuleInterface::class)->getEntityConnectionName()
        );
    }
}
