<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor\Rule;

use Aheadworks\Afptc\Model\ResourceModel\Rule\Collection;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Class CustomerGroupFilter
 *
 * @package Aheadworks\Afptc\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor\Rule
 */
class CustomerGroupFilter implements CustomFilterInterface
{
    /**
     * Apply custom group filter to collection
     *
     * @param Filter $filter
     * @param AbstractDb $collection
     * @return bool
     */
    public function apply(Filter $filter, AbstractDb $collection)
    {
        /** @var Collection $collection */
        $collection->addCustomerGroupFilter($filter->getValue());

        return true;
    }
}
