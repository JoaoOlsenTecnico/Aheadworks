<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model;

use Magento\Framework\Api\Search\SearchCriteria;

/**
 * Class Report
 *
 * @package Aheadworks\Afptc\Model
 */
class Report
{
    /**
     * @var array
     */
    private $reports;

    /**
     * @param array $reports
     */
    public function __construct($reports = [])
    {
        $this->reports = $reports;
    }

    /**
     * Retrieve report data
     *
     * @param SearchCriteria $searchCriteria
     * @return array
     */
    public function getStatistics($searchCriteria)
    {
        $data = [];
        foreach ($this->reports as $report) {
            $data = array_merge($data, $report->getStatistics($searchCriteria));
        }
        return $data;
    }
}
