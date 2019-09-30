<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Report\Filter;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\Report\Filter\Processor;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ProcessorTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Report\Filter
 */
class ProcessorTest extends TestCase
{
    /**
     * @var Processor
     */
    private $model;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagerMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->model = $objectManager->getObject(
            Processor::class,
            [
                'storeManager' => $this->storeManagerMock
            ]
        );
    }

    /**
     * Test getStoreIds method
     *
     * @param string $field
     * @param int|null $websiteId
     * @param int|null $storeId
     * @param array|null $expected
     * @dataProvider getStoreIdsDataProvider
     */
    public function testGetStoreIds($field, $websiteId, $storeId, $expected)
    {
        $searchCriteriaMock = $this->getMockForAbstractClass(SearchCriteriaInterface::class);
        $this->initWebsiteMethod($searchCriteriaMock, $field, $websiteId);
        if ($websiteId) {
            $storeMock = $this->getMockForAbstractClass(StoreInterface::class);
            $this->storeManagerMock->expects($this->once())
                ->method('getStores')
                ->with(false)
                ->willReturn([$storeMock]);
            $storeMock->expects($this->once())
                ->method('getWebsiteId')
                ->willReturn($websiteId);
            $storeMock->expects($this->once())
                ->method('getId')
                ->willReturn($storeId);
        }

        $this->assertEquals($expected, $this->model->getStoreIds($searchCriteriaMock));
    }

    /**
     * Data provider for getStoreIds test
     *
     * @return array
     */
    public function getStoreIdsDataProvider()
    {
        return [
            [RuleInterface::WEBSITE_IDS, 1, 1, [1]],
            [RuleInterface::FROM_DATE, null, null, null]
        ];
    }

    /**
     * Test getWebsiteId method
     *
     * @param string $field
     * @param int|null $value
     * @dataProvider getWebsiteIdDataProvider
     */
    public function testGetWebsiteId($field, $value)
    {
        $searchCriteriaMock = $this->getMockForAbstractClass(SearchCriteriaInterface::class);
        $this->initWebsiteMethod($searchCriteriaMock, $field, $value);

        $this->assertEquals($value, $this->model->getWebsiteId($searchCriteriaMock));
    }

    /**
     * Initialize website method
     *
     * @param $searchCriteriaMock
     * @param string $field
     * @param int|null $value
     * @return void
     */
    private function initWebsiteMethod($searchCriteriaMock, $field, $value)
    {
        $filterGroupMock = $this->createPartialMock(FilterGroup::class, ['getFilters']);
        $filterMock = $this->createPartialMock(Filter::class, ['getField', 'getValue']);

        $searchCriteriaMock->expects($this->once())
            ->method('getFilterGroups')
            ->willReturn([$filterGroupMock]);
        $filterGroupMock->expects($this->once())
            ->method('getFilters')
            ->willReturn([$filterMock]);
        $filterMock->expects($this->once())
            ->method('getField')
            ->willReturn($field);
        if ($field == RuleInterface::WEBSITE_IDS) {
            $filterMock->expects($this->once())
                ->method('getValue')
                ->willReturn($value);
        }
    }

    /**
     * Data provider for getWebsiteId test
     *
     * @return array
     */
    public function getWebsiteIdDataProvider()
    {
        return [
            [RuleInterface::WEBSITE_IDS, 1],
            [RuleInterface::FROM_DATE, null]
        ];
    }
}
