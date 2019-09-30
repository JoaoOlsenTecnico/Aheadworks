<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\AbstractModel as MagentoFrameworkAbstractModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct\RuleProductInterface;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Eav\Model\Entity\AbstractEntity;
use Aheadworks\Afptc\Model\Rule as RuleModel;
use Magento\Eav\Model\Entity\Context;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Eav\Api\Data\AttributeInterface;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;

/**
 * Class Rule
 *
 * @package Aheadworks\Afptc\Model\ResourceModel
 */
class Rule extends AbstractEntity
{
    /**#@+
     * Constants defined for table names
     */
    const MAIN_TABLE_NAME = 'aw_afptc_rule_entity';
    const MAIN_TABLE_INT_NAME = 'aw_afptc_rule_entity_int';
    const MAIN_TABLE_VARCHAR_NAME = 'aw_afptc_rule_entity_varchar';
    const MAIN_TABLE_TEXT_NAME = 'aw_afptc_rule_entity_text';
    const WEBSITE_TABLE_NAME = 'aw_afptc_rule_website';
    const CUSTOMER_GROUP_TABLE_NAME = 'aw_afptc_rule_customer_group';
    const PROMO_PRODUCT_TABLE_NAME = 'aw_afptc_rule_promo_product';
    const STORE_PROMO_TABLE_NAME = 'aw_afptc_rule_store_promo';
    const PRODUCT_TABLE_NAME = 'aw_afptc_rule_product';
    const PRODUCT_IDX_TABLE_NAME = 'aw_afptc_rule_product_idx';
    const PRODUCT_ATTRIBUTE_TABLE_NAME = 'aw_afptc_rule_product_attribute';
    /**#@-*/

    /**
     * @var array
     */
    protected $entityArguments = [];

    /**
     * EntityManager
     */
    private $entityManager;

    /**
     * @param Context $context
     * @param EntityManager $entityManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        EntityManager $entityManager,
        $data = []
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($context, $data);
    }

    /**
     * Entity type getter and lazy loader
     *
     * @return \Magento\Eav\Model\Entity\Type
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(RuleModel::ENTITY);
        }
        return parent::getEntityType();
    }

    /**
     * Save an object
     *
     * @param MagentoFrameworkAbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(MagentoFrameworkAbstractModel $object)
    {
        $object->validateBeforeSave();
        $object->beforeSave();
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * Load an object
     *
     * @param MagentoFrameworkAbstractModel $object
     * @param int $objectId
     * @param string $field
     * @return $this
     */
    public function load($object, $objectId, $field = null)
    {
        if (!empty($objectId)) {
            $arguments = $this->getArgumentsForEntity();
            $this->entityManager->load($object, $objectId, $arguments);
            $object->afterLoad();
        }
        return $this;
    }

    /**
     * Delete an object
     *
     * @param MagentoFrameworkAbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function delete($object)
    {
        $this->entityManager->delete($object);
        return $this;
    }

    /**
     * Retrieve arguments array for entity
     *
     * @return array
     */
    protected function getArgumentsForEntity()
    {
        return $this->entityArguments;
    }

    /**
     * Set arguments array for entity
     *
     * @param string $key
     * @param mixed $value
     */
    public function setArgumentsForEntity($key, $value)
    {
        $this->entityArguments[$key] = $value;
    }

    /**
     * Retrieve sorted rules data by rule priority on few filters
     *
     * @param int $productId
     * @param int $customerGroupId
     * @param int $storeId
     * @param string $currentDate
     * @return array
     */
    public function getSortedRuleDataForProduct($productId, $customerGroupId, $storeId, $currentDate)
    {
        $select = $this->getQueryForSortedRulesForProduct($productId, $customerGroupId, $storeId, $currentDate);

        return $this->getConnection()->fetchAll($select);
    }

    /**
     * Retrieve query for sorted rules by rule priority on few filters from indexer table
     *
     * @param int $productId
     * @param int $customerGroupId
     * @param int $storeId
     * @param string $currentDate
     * @return Select
     */
    public function getQueryForSortedRulesForProduct($productId, $customerGroupId, $storeId, $currentDate)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(
                $this->getTable(self::PRODUCT_TABLE_NAME),
                [
                    RuleProductInterface::RULE_ID,
                    RuleProductInterface::PROMO_OFFER_INFO_TEXT,
                    RuleProductInterface::PROMO_IMAGE,
                    RuleProductInterface::PROMO_IMAGE_ALT_TEXT,
                    RuleProductInterface::PROMO_HEADER,
                    RuleProductInterface::PROMO_DESCRIPTION,
                    RuleProductInterface::PROMO_URL,
                    RuleProductInterface::PROMO_URL_TEXT,
                ]
            )->where(RuleProductInterface::STORE_ID . ' = ?', $storeId)
            ->where(RuleProductInterface::CUSTOMER_GROUP_ID . ' = ' . GroupInterface::CUST_GROUP_ALL .
                ' OR ' . RuleProductInterface::CUSTOMER_GROUP_ID . ' = ?', $customerGroupId)
            ->where(RuleProductInterface::PRODUCT_ID . ' = ?', $productId)
            ->where(
                'ISNULL(' . RuleProductInterface::FROM_DATE . ') OR ' . RuleProductInterface::FROM_DATE . ' <= ?',
                $currentDate
            )->where(
                'ISNULL(' . RuleProductInterface::TO_DATE . ') OR ' . RuleProductInterface::TO_DATE . ' >= ?',
                $currentDate
            )->order(RuleProductInterface::PRIORITY . ' ASC')
            ->order(RuleProductInterface::RULE_ID . ' DESC')
            ->limit(1);

        return $select;
    }

    /**
     * Modify on sale sorted rule query
     *
     * @param Select[] $queries
     * @return Select|\Zend_Db_Select
     * @throws \Zend_Db_Select_Exception
     */
    public function mergeSortedRuleQueriesForOnSale($queries)
    {
        foreach ($queries as &$query) {
            $query = '(' . $query . ')';
        }

        $mergedQueries = $this->getConnection()
            ->select()
            ->union($queries, Select::SQL_UNION_ALL)
            ->order(RuleProductInterface::PRIORITY . ' ASC')
            ->order('label_id DESC');

        return $mergedQueries;
    }

    /**
     * Retrieve sorted rules sql query by rule priority on few filters for on sale
     *
     * @param int $productId
     * @param int $customerGroupId
     * @param int $storeId
     * @param string $currentDate
     * @return Select
     */
    public function getSortedRulesQueryForOnSale($productId, $customerGroupId, $storeId, $currentDate)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(
                $this->getTable(self::PRODUCT_TABLE_NAME),
                [
                    'label_id' => RuleProductInterface::PROMO_ON_SALE_LABEL_ID,
                    'label_text_large' => RuleProductInterface::PROMO_ON_SALE_LABEL_TEXT_LARGE,
                    'label_text_medium' => RuleProductInterface::PROMO_ON_SALE_LABEL_TEXT_MEDIUM,
                    'label_text_small' => RuleProductInterface::PROMO_ON_SALE_LABEL_TEXT_SMALL,
                    'priority' => RuleProductInterface::PRIORITY
                ]
            )->where(RuleProductInterface::STORE_ID . ' = ?', $storeId)
            ->where(RuleProductInterface::CUSTOMER_GROUP_ID . ' = ' . GroupInterface::CUST_GROUP_ALL .
                ' OR ' . RuleProductInterface::CUSTOMER_GROUP_ID . ' = ?', $customerGroupId)
            ->where(RuleProductInterface::PRODUCT_ID . ' = ?', $productId)
            ->where(
                'ISNULL(' . RuleProductInterface::FROM_DATE . ') OR ' . RuleProductInterface::FROM_DATE . ' <= ?',
                $currentDate
            )->where(
                'ISNULL(' . RuleProductInterface::TO_DATE . ') OR ' . RuleProductInterface::TO_DATE . ' >= ?',
                $currentDate
            )->where(RuleProductInterface::PROMO_ON_SALE_LABEL_ID . ' IS NOT NULL')
            ->order(RuleProductInterface::PRIORITY . ' ASC')
            ->order('label_id DESC');

        return $select;
    }

    /**
     * Return codes of all product attributes currently used in all rule conditions
     *
     * @return array
     */
    public function getActiveAttributes()
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            ['a' => $this->getTable(self::PRODUCT_ATTRIBUTE_TABLE_NAME)],
            new \Zend_Db_Expr('DISTINCT ea.' . AttributeInterface::ATTRIBUTE_CODE)
        )->joinInner(
            ['ea' => $this->getTable('eav_attribute')],
            'ea.' . AttributeInterface::ATTRIBUTE_ID . ' = a.' . AttributeInterface::ATTRIBUTE_ID,
            []
        );
        return $connection->fetchAll($select);
    }

    /**
     * Retrieve rule ids which contain product ids
     *
     * @param array $productIds
     * @return array
     * @throws \Exception
     */
    public function getRuleIdsByPromoProducts($productIds)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
        ->from(
            ['tbl_main' => $this->getTable(self::PROMO_PRODUCT_TABLE_NAME)],
            [RuleProductInterface::RULE_ID]
        )->join(
            ['cpe' => $this->getTable('catalog_product_entity')],
            'cpe.sku = tbl_main.' . RulePromoProductInterface::PRODUCT_SKU,
            []
        )->where(
            $connection->prepareSqlCondition('cpe.entity_id', ['in' => $productIds])
        )->group(RuleProductInterface::RULE_ID);

        return $connection->fetchCol($select);
    }
}
