<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Listing;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\RuleRepositoryInterface;
use Aheadworks\Afptc\Model\Source\Rule\Status;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\SortOrder;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Collection as RuleCollection;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Afptc\Model\Rule\Listing\Builder;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\Api\SearchCriteria;
use Aheadworks\Afptc\Api\Data\RuleSearchResultsInterface;

/**
 * Class BuilderTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Listing
 */
class BuilderTest extends TestCase
{
    /**
     * @var Builder
     */
    private $model;

    /**
     * @var RuleRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleRepositoryMock;

    /**
     * @var SortOrderBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $sortOrderBuilderMock;

    /**
     * @var SearchCriteriaBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaBuilderMock;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagerMock;

    /**
     * @var DateTime|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dateTimeMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->searchCriteriaBuilderMock = $this->createPartialMock(
            SearchCriteriaBuilder::class,
            ['setField', 'setDirection', 'addFilter', 'addSortOrder', 'create']
        );
        $this->sortOrderBuilderMock = $this->createPartialMock(
            SortOrderBuilder::class,
            ['setField', 'setDirection', 'create']
        );
        $this->ruleRepositoryMock = $this->getMockForAbstractClass(RuleRepositoryInterface::class);
        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->dateTimeMock = $this->createPartialMock(DateTime::class, ['gmtDate']);

        $this->model = $objectManager->getObject(
            Builder::class,
            [
                'ruleRepository' => $this->ruleRepositoryMock,
                'sortOrderBuilder' => $this->sortOrderBuilderMock,
                'searchCriteriaBuilder' => $this->searchCriteriaBuilderMock,
                'storeManager' => $this->storeManagerMock,
                'dateTime' => $this->dateTimeMock
            ]
        );
    }

    public function testGetActiveRules()
    {
        $storeId = 4;
        $customerGroupId = 1;
        $websiteId = 2;
        $storeMock = $this->getMockForAbstractClass(StoreInterface::class);
        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->with($storeId)
            ->willReturn($storeMock);
        $storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);

        $date = '12-12-2013';
        $this->dateTimeMock->expects($this->once())
            ->method('gmtDate')
            ->with(StdlibDateTime::DATE_PHP_FORMAT)
            ->willReturn($date);
        $this->searchCriteriaBuilderMock->expects($this->any())
            ->method('addFilter')
            ->withConsecutive(
                [RuleInterface::WEBSITE_IDS, $websiteId],
                [RuleInterface::CUSTOMER_GROUP_IDS, $customerGroupId],
                [RuleInterface::IS_ACTIVE, Status::ENABLED],
                [RuleCollection::DATE, $date]
            )->willReturnSelf();

        $this->sortOrderBuilderMock->expects($this->once())
            ->method('setField')
            ->with(RuleInterface::PRIORITY)
            ->willReturnSelf();
        $this->sortOrderBuilderMock->expects($this->once())
            ->method('setDirection')
            ->with(SortOrder::SORT_ASC)
            ->willReturnSelf();
        $this->sortOrderBuilderMock->expects($this->once())
            ->method('create')
            ->willReturnSelf();

        $this->searchCriteriaBuilderMock->expects($this->any())
            ->method('addSortOrder')
            ->with($this->sortOrderBuilderMock)
            ->willReturnSelf();
        $searchCriteriaMock = $this->createMock(SearchCriteria::class);
        $this->searchCriteriaBuilderMock->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteriaMock);

        $labelSearchResultsMock = $this->getMockForAbstractClass(RuleSearchResultsInterface::class);
        $this->ruleRepositoryMock->expects($this->once())
            ->method('getList')
            ->with($searchCriteriaMock)
            ->willReturn($labelSearchResultsMock);

        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $labelSearchResultsMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$ruleMock]);

        $this->assertEquals([$ruleMock], $this->model->getActiveRules($storeId, $customerGroupId));
    }
}
