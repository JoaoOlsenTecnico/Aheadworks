<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Report;

use Magento\Framework\Api\Search\SearchCriteria;

/**
 * Interface ReportStatisticInterface
 *
 * @package Aheadworks\Afptc\Model\Report
 */
interface ReportStatisticInterface
{
    /**
     * Retrieve report statistics
     *
     * @param SearchCriteria $searchCriteria
     * @return array
     */
    public function getStatistics($searchCriteria);
}
