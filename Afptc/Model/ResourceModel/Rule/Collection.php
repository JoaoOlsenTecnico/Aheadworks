<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule;

use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;
use Aheadworks\Afptc\Model\ResourceModel\AbstractEavCollection;
use Aheadworks\Afptc\Model\ResourceModel\Rule as ResourceRule;
use Aheadworks\Afptc\Model\Rule as RuleModel;
use Magento\Framework\Data\Collection\EntityFactory as FrameworkEntityFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\Rule\Processor;
use Magento\Eav\Model\Config as EavConfig;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\Website\ReadHandler as WebsiteReadHandler;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\CustomerGroup\ReadHandler as CustomerGroupReadHandler;
use Magento\Framework\App\ResourceConnection;
use Magento\Eav\Model\EntityFactory;
use Magento\Eav\Model\ResourceModel\Helper;
use Magento\Framework\Validator\UniversalFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Class Collection
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule
 */
class Collection extends AbstractEavCollection
{
    /**
     * Constant defined for current date. It is used for filtering.
     */
    const DATE = 'date';

    /**
     * @var string
     */
    protected $_idFieldName = RuleInterface::RULE_ID;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(RuleModel::class, ResourceRule::class);
    }

    /**
     * @param FrameworkEntityFactory $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param EavConfig $eavConfig
     * @param ResourceConnection $resource
     * @param EntityFactory $eavEntityFactory
     * @param Helper $resourceHelper
     * @param UniversalFactory $universalFactory
     * @param Processor $processor
     * @param AdapterInterface|null $connection
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        FrameworkEntityFactory $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        EavConfig $eavConfig,
        ResourceConnection $resource,
        EntityFactory $eavEntityFactory,
        Helper $resourceHelper,
        UniversalFactory $universalFactory,
        Processor $processor,
        AdapterInterface $connection = null
    ) {
        $this->processor = $processor;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $connection
        );
    }

    /**
     * {@inheritdoc}
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (in_array($field, [RuleInterface::WEBSITE_IDS])) {
            $this->addWebsiteFilter($condition);
            return $this;
        }
        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add filter by website
     *
     * @param array
     * @return $this
     */
    public function addWebsiteFilter($websiteIds)
    {
        $this->addFilter(RuleInterface::WEBSITE_IDS, ['in' => $websiteIds], 'public');

        return $this;
    }

    /**
     * Add filter by date
     *
     * @param array
     * @return $this
     */
    public function addDateFilter($currentDate)
    {
        $fromDateField = 'e.' . RuleInterface::FROM_DATE;
        $fromDateWhere = [
            $this->_getConditionSql($fromDateField, ['lteq' => $currentDate]),
            $this->_getConditionSql($fromDateField, ['null' => true]),
        ];
        $this->addFilter(RuleInterface::FROM_DATE, implode(' OR ', $fromDateWhere), 'string');

        $toDateField = 'e.' . RuleInterface::TO_DATE;
        $toDateWhere = [
            $this->_getConditionSql($toDateField, ['gteq' => $currentDate]),
            $this->_getConditionSql($toDateField, ['null' => true]),
        ];
        $this->addFilter(RuleInterface::TO_DATE, implode(' OR ', $toDateWhere), 'string');

        return $this;
    }

    /**
     * Add filter by customer group
     *
     * @param array
     * @return $this
     */
    public function addCustomerGroupFilter($customerGroupIds)
    {
        $field = $this->generateLinkageTableName(RuleInterface::CUSTOMER_GROUP_IDS)
            . '.' . CustomerGroupReadHandler::CUSTOMER_GROUP_ID;
        $where = [
            $this->_getConditionSql($field, ['in' => $customerGroupIds])
        ];

        $this->addFilter(RuleInterface::CUSTOMER_GROUP_IDS, implode(' OR ', $where), 'string');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterLoad()
    {
        $this->attachRelationTable(
            ResourceRule::WEBSITE_TABLE_NAME,
            RuleInterface::RULE_ID,
            RuleInterface::RULE_ID,
            WebsiteReadHandler::WEBSITE_ID,
            RuleInterface::WEBSITE_IDS
        );
        $this->attachRelationTable(
            ResourceRule::CUSTOMER_GROUP_TABLE_NAME,
            RuleInterface::RULE_ID,
            RuleInterface::RULE_ID,
            CustomerGroupReadHandler::CUSTOMER_GROUP_ID,
            RuleInterface::CUSTOMER_GROUP_IDS
        );
        $this->attachRelationTable(
            ResourceRule::PROMO_PRODUCT_TABLE_NAME,
            RuleInterface::RULE_ID,
            RuleInterface::RULE_ID,
            [
                RulePromoProductInterface::PRODUCT_SKU,
                RulePromoProductInterface::OPTION,
                RulePromoProductInterface::POSITION
            ],
            RuleInterface::PROMO_PRODUCTS,
            RulePromoProductInterface::POSITION
        );

        /** @var RuleModel $item */
        foreach ($this as $item) {
            $this->processor->prepareDataAfterLoad($item);
            $item->setStoreId($this->getStoreId());
            $item->setIdFieldName(RuleInterface::RULE_ID);
        }
        return parent::_afterLoad();
    }

    /**
     * {@inheritdoc}
     */
    protected function _renderFiltersBefore()
    {
        $this->joinLinkageTable(
            ResourceRule::WEBSITE_TABLE_NAME,
            RuleInterface::RULE_ID,
            RuleInterface::RULE_ID,
            RuleInterface::WEBSITE_IDS,
            WebsiteReadHandler::WEBSITE_ID
        );
        $this->joinLinkageTable(
            ResourceRule::CUSTOMER_GROUP_TABLE_NAME,
            RuleInterface::RULE_ID,
            RuleInterface::RULE_ID,
            RuleInterface::CUSTOMER_GROUP_IDS,
            CustomerGroupReadHandler::CUSTOMER_GROUP_ID
        );
        $this->joinLinkageTable(
            ResourceRule::PROMO_PRODUCT_TABLE_NAME,
            RuleInterface::RULE_ID,
            RuleInterface::RULE_ID,
            RuleInterface::PROMO_PRODUCTS,
            RulePromoProductInterface::PRODUCT_SKU
        );
        parent::_renderFiltersBefore();
    }
}
