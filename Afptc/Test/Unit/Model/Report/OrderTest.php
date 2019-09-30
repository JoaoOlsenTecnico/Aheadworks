<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Report;

use Aheadworks\Afptc\Model\Report\Order;
use Magento\Framework\Api\Search\SearchCriteria;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\ResourceModel\Report\OrderFactory as OrderReportFactory;
use Aheadworks\Afptc\Model\ResourceModel\Report\Order as OrderReport;
use Aheadworks\Afptc\Model\Report\Filter\Processor;

/**
 * Class OrderTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Report
 */
class OrderTest extends TestCase
{
    /**
     * @var Order
     */
    private $model;

    /**
     * @var OrderReportFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $orderReportFactoryMock;

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
        $this->orderReportFactoryMock = $this->createPartialMock(OrderReportFactory::class, ['create']);
        $this->filterProcessorMock = $this->createPartialMock(Processor::class, ['getStoreIds']);
        $this->model = $objectManager->getObject(
            Order::class,
            [
                'orderReportFactory' => $this->orderReportFactoryMock,
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
    public function testGetStatistics($storeIds)
    {
        $orderReportStat = [
            'monthly_value' => 1,
            'order_qty' => 1,
            'item_qty' => 1
        ];
        $promoOrderReportStat = [
            'monthly_value' => 1,
            'order_qty' => 1,
            'item_qty' => 1
        ];

        $searchCriteriaMock = $this->getMockForAbstractClass(SearchCriteria::class);
        $this->filterProcessorMock->expects($this->once())
            ->method('getStoreIds')
            ->with($searchCriteriaMock)
            ->willReturn($storeIds);

        $orderReport = $this->createPartialMock(
            OrderReport::class,
            ['addDateFilter', 'addStoreFilter', 'getStatistics']
        );
        $this->orderReportFactoryMock->expects($this->at(0))
            ->method('create')
            ->willReturn($orderReport);

        $promoOrderReport = $this->createPartialMock(
            OrderReport::class,
            ['addDateFilter', 'addStoreFilter', 'getStatistics']
        );
        $this->orderReportFactoryMock->expects($this->at(1))
            ->method('create')
            ->willReturn($promoOrderReport);

        if ($storeIds) {
            $orderReport->expects($this->once())
                ->method('addStoreFilter')
                ->with($storeIds)
                ->willReturnSelf();
            $promoOrderReport->expects($this->once())
                ->method('addStoreFilter')
                ->with($storeIds)
                ->willReturnSelf();
        }
        $convertToGlobalRate = empty($storeIds);
        $orderReport->expects($this->once())
            ->method('getStatistics')
            ->with(false, $convertToGlobalRate)
            ->willReturn($orderReportStat);
        $promoOrderReport->expects($this->once())
            ->method('getStatistics')
            ->with(true, $convertToGlobalRate)
            ->willReturn($promoOrderReportStat);

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
            [[[1, 2]]],
            [[null]]
        ];
    }
}
