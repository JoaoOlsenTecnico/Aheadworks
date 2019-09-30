<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Listing;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\RuleRepositoryInterface;
use Aheadworks\Afptc\Model\Source\Rule\Status;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\SortOrder;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Collection as RuleCollection;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Builder
 *
 * @package Aheadworks\Afptc\Model\Rule\Listing
 */
class Builder
{
    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @param RuleRepositoryInterface $ruleRepository
     * @param SortOrderBuilder $sortOrderBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StoreManagerInterface $storeManager
     * @param DateTime $dateTime
     */
    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        SortOrderBuilder $sortOrderBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManagerInterface $storeManager,
        DateTime $dateTime
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
        $this->dateTime = $dateTime;
    }

    /**
     * Retrieve active rules by priority
     *
     * @param int $storeId
     * @param int $customerGroupId
     * @return RuleInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getActiveRules($storeId, $customerGroupId)
    {
        $websiteId = $this->storeManager->getStore($storeId)->getWebsiteId();
        $this->getSearchCriteriaBuilder()
            ->addFilter(RuleInterface::WEBSITE_IDS, $websiteId)
            ->addFilter(RuleInterface::CUSTOMER_GROUP_IDS, $customerGroupId);

        $activeRules = $this->ruleRepository
            ->getList($this->buildSearchCriteria(), $storeId)
            ->getItems();

        return $activeRules;
    }

    /**
     * Retrieves search criteria builder
     *
     * @return SearchCriteriaBuilder
     */
    public function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilder;
    }

    /**
     * Build search criteria
     *
     * @return \Magento\Framework\Api\SearchCriteria
     */
    private function buildSearchCriteria()
    {
        $this->prepareSearchCriteriaBuilder();
        return $this->searchCriteriaBuilder->create();
    }

    /**
     * Prepares search criteria builder
     *
     * @return void
     */
    private function prepareSearchCriteriaBuilder()
    {
        $sortOrder = $this->sortOrderBuilder
            ->setField(RuleInterface::PRIORITY)
            ->setDirection(SortOrder::SORT_ASC)
            ->create();

        $this->searchCriteriaBuilder
            ->addFilter(RuleInterface::IS_ACTIVE, Status::ENABLED)
            ->addFilter(
                RuleCollection::DATE,
                $this->dateTime->gmtDate(StdlibDateTime::DATE_PHP_FORMAT)
            )->addSortOrder($sortOrder);
    }
}
