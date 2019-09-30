<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule;

use Aheadworks\Afptc\Model\Report;
use Aheadworks\Afptc\Ui\DataProvider\Rule\Report\PriceFormatResolver;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Api\Search\ReportingInterface;

/**
 * Class ListingDataProvider
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule
 */
class ListingDataProvider extends DataProvider
{
    /**
     * @var PriceFormatResolver
     */
    private $priceFormatResolver;

    /**
     * @var Report
     */
    private $report;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param Report $report
     * @param PriceFormatResolver $priceFormatResolver
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        Report $report,
        PriceFormatResolver $priceFormatResolver,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->report = $report;
        $this->priceFormatResolver = $priceFormatResolver;
    }

    /**
     * {@inheritdoc}
     */
    protected function searchResultToOutput(SearchResultInterface $searchResult)
    {
        $arrItems = parent::searchResultToOutput($searchResult);
        $arrItems['topTotals'][] = $this->report->getStatistics($this->getSearchCriteria());
        $arrItems['priceFormat'] = $this->priceFormatResolver->resolve($this->getSearchCriteria());

        return $arrItems;
    }
}
