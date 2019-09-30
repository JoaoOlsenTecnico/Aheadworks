<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Report\Filter;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Framework\Api\Search\SearchCriteria;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Processor
 *
 * @package Aheadworks\Afptc\Model\Report\Filter
 */
class Processor
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve all stores for specific website
     *
     * @param SearchCriteria $searchCriteria
     * @return array|null
     */
    public function getStoreIds($searchCriteria)
    {
        $storeIds = null;
        if ($websiteId = $this->getWebsiteId($searchCriteria)) {
            $storeIds = [];
            $stores = $this->storeManager->getStores(false);
            foreach ($stores as $store) {
                if ($store->getWebsiteId() == $websiteId) {
                    $storeIds[] = $store->getId();
                }
            }
        }
        return $storeIds;
    }

    /**
     * Retrieve website id
     *
     * @param SearchCriteria $searchCriteria
     * @return int|null
     */
    public function getWebsiteId($searchCriteria)
    {
        $websiteId = null;
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() == RuleInterface::WEBSITE_IDS) {
                    $websiteId = $filter->getValue();
                }
            }
        }

        return $websiteId;
    }
}
