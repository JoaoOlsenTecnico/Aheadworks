<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\PromoProduct;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResourceModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\AbstractReadHandler;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\EntityManager\MetadataPool;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;

/**
 * Class ReadHandler
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\PromoProduct
 */
class ReadHandler extends AbstractReadHandler
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        MetadataPool $metadataPool,
        ResourceConnection $resourceConnection,
        DataObjectHelper $dataObjectHelper
    ) {
        parent::__construct($metadataPool, $resourceConnection);
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->initTable(RuleResourceModel::PROMO_PRODUCT_TABLE_NAME);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function process($entity, $arguments)
    {
        $ruleData = [
            RuleInterface::PROMO_PRODUCTS => $this->getPromoProductData($entity->getRuleId())
        ];
        $this->dataObjectHelper->populateWithArray(
            $entity,
            $ruleData,
            RuleInterface::class
        );
    }

    /**
     * Retrieve promo product ids
     *
     * @param int $entityId
     * @return array
     * @throws \Exception
     */
    private function getPromoProductData($entityId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->tableName)
            ->where(RuleInterface::RULE_ID . ' = :id')
            ->order(RulePromoProductInterface::POSITION . ' ASC');
        return $connection->fetchAll($select, ['id' => $entityId]);
    }
}
