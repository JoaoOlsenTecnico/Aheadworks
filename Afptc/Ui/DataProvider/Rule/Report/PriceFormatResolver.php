<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\Report;

use Aheadworks\Afptc\Model\Report\Filter\Processor;
use Magento\Framework\Api\Search\SearchCriteria;
use Magento\Framework\Locale\FormatInterface;
use Aheadworks\Afptc\Ui\ScopeCurrency;

/**
 * Class PriceFormatResolver
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\Report
 */
class PriceFormatResolver
{
    /**
     * @var FormatInterface
     */
    private $localeFormat;

    /**
     * @var ScopeCurrency
     */
    private $scopeCurrency;

    /**
     * @var Processor
     */
    private $filterProcessor;

    /**
     * @param FormatInterface $localeFormat
     * @param ScopeCurrency $scopeCurrency
     * @param Processor $filterProcessor
     */
    public function __construct(
        FormatInterface $localeFormat,
        ScopeCurrency $scopeCurrency,
        Processor $filterProcessor
    ) {
        $this->localeFormat = $localeFormat;
        $this->scopeCurrency = $scopeCurrency;
        $this->filterProcessor = $filterProcessor;
    }

    /**
     * Resolve price format
     *
     * @param SearchCriteria $searchCriteria
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resolve($searchCriteria)
    {
        $storeIds = $this->filterProcessor->getStoreIds($searchCriteria);
        $currencyCode = $this->scopeCurrency->getCurrencyCode($storeIds);

        return $this->localeFormat->getPriceFormat(null, $currencyCode);
    }
}
