<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Report;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Aheadworks\Afptc\Model\ResourceModel\Report\RuleFactory as RuleReportFactory;
use Aheadworks\Afptc\Model\ResourceModel\Report\Rule as RuleReport;
use Aheadworks\Afptc\Model\Report\Filter\Processor;

/**
 * Class Rule
 *
 * @package Aheadworks\Afptc\Model\Report
 */
class Rule implements ReportStatisticInterface
{
    /**
     * @var RuleReportFactory
     */
    private $ruleReportFactory;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var Processor
     */
    private $filterProcessor;

    /**
     * @param RuleReportFactory $ruleReportFactory
     * @param DateTime $dateTime
     * @param Processor $filterProcessor
     */
    public function __construct(
        RuleReportFactory $ruleReportFactory,
        DateTime $dateTime,
        Processor $filterProcessor
    ) {
        $this->dateTime = $dateTime;
        $this->ruleReportFactory = $ruleReportFactory;
        $this->filterProcessor = $filterProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatistics($searchCriteria)
    {
        $currentDate = $this->dateTime->gmtDate(StdlibDateTime::DATE_PHP_FORMAT);
        $websiteId = $this->filterProcessor->getWebsiteId($searchCriteria);
        /** @var RuleReport $ruleReport */
        $ruleReport = $this->ruleReportFactory->create();
        $ruleReport->addDateFilter($currentDate);

        if (!empty($websiteId)) {
            $ruleReport->addFieldToFilter(RuleInterface::WEBSITE_IDS, ['in' => [$websiteId]]);
        }

        return $ruleReport->getStatistics();
    }
}
