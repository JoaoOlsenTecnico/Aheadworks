<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Report;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\Report\Rule;
use Magento\Framework\Api\Search\SearchCriteria;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\ResourceModel\Report\RuleFactory as RuleReportFactory;
use Aheadworks\Afptc\Model\Report\Filter\Processor;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Aheadworks\Afptc\Model\ResourceModel\Report\Rule as RuleReport;

/**
 * Class RuleTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Report
 */
class RuleTest extends TestCase
{
    /**
     * @var Rule
     */
    private $model;

    /**
     * @var RuleReportFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleReportFactoryMock;

    /**
     * @var DateTime|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dateTimeMock;

    /**
     * @var Processor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $filterProcessorMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->dateTimeMock = $this->createPartialMock(DateTime::class, ['gmtDate']);
        $this->ruleReportFactoryMock = $this->createPartialMock(RuleReportFactory::class, ['create']);
        $this->filterProcessorMock = $this->createPartialMock(Processor::class, ['getWebsiteId']);
        $this->model = $objectManager->getObject(
            Rule::class,
            [
                'dateTime' => $this->dateTimeMock,
                'ruleReportFactory' => $this->ruleReportFactoryMock,
                'filterProcessor' => $this->filterProcessorMock,
            ]
        );
    }

    /**
     * Test getStatistics method
     *
     * @param int|null websiteId
     * @dataProvider getStatisticsDataProvider
     */
    public function testGetStatistics($websiteId)
    {
        $currentDate = 'date';
        $searchCriteriaMock = $this->getMockForAbstractClass(SearchCriteria::class);

        $this->dateTimeMock->expects($this->once())
            ->method('gmtDate')
            ->with(StdlibDateTime::DATE_PHP_FORMAT)
            ->willReturn($currentDate);
        $this->filterProcessorMock->expects($this->once())
            ->method('getWebsiteId')
            ->with($searchCriteriaMock)
            ->willReturn($websiteId);

        $ruleReport = $this->createPartialMock(
            RuleReport::class,
            ['addDateFilter', 'addFieldToFilter', 'getStatistics']
        );
        $this->ruleReportFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($ruleReport);

        $ruleReport->expects($this->once())
            ->method('addDateFilter')
            ->with($currentDate)
            ->willReturnSelf();
        if ($websiteId) {
            $ruleReport->expects($this->once())
                ->method('addFieldToFilter')
                ->with(RuleInterface::WEBSITE_IDS, ['in' => [$websiteId]])
                ->willReturnSelf();
        }
        $ruleReport->expects($this->once())
            ->method('getStatistics')
            ->willReturn([]);

        $this->assertTrue(is_array($this->model->getStatistics($searchCriteriaMock)));
    }

    /**
     * Data provider for getStatistics test
     *
     * @return array
     */
    public function getStatisticsDataProvider()
    {
        return [
            [[1]],
            [[null]]
        ];
    }
}
