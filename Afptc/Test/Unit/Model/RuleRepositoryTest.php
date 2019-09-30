<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model;

use Aheadworks\Afptc\Api\Data\RuleSearchResultsInterface;
use Aheadworks\Afptc\Model\Rule;
use Aheadworks\Afptc\Model\RuleRepository;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleInterfaceFactory;
use Aheadworks\Afptc\Api\Data\RuleSearchResultsInterfaceFactory;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResourceModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Collection as RuleCollection;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Class RuleRepository
 *
 * @package Aheadworks\Afptc\Test\Unit\Model
 */
class RuleRepositoryTest extends TestCase
{
    /**
     * @var RuleRepository
     */
    private $model;

    /**
     * @var RuleResourceModel|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resourceMock;

    /**
     * @var RuleInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleInterfaceFactoryMock;

    /**
     * @var RuleCollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleCollectionFactoryMock;

    /**
     * @var RuleSearchResultsInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchResultsFactoryMock;

    /**
     * @var JoinProcessorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $extensionAttributesJoinProcessorMock;

    /**
     * @var CollectionProcessorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $collectionProcessorMock;

    /**
     * @var DataObjectHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataObjectHelperMock;

    /**
     * @var DataObjectProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataObjectProcessorMock;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagerMock;

    /**
     * @var StoreInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeMock;

    /**
     * @var array
     */
    private $ruleData = [
        'rule_id' => 1,
        'name' => 'test rule',
    ];

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $storeId = 3;
        $objectManager = new ObjectManager($this);
        $this->resourceMock = $this->createPartialMock(
            RuleResourceModel::class,
            ['save', 'load', 'delete', 'setArgumentsForEntity']
        );
        $this->ruleInterfaceFactoryMock = $this->createPartialMock(RuleInterfaceFactory::class, ['create']);
        $this->ruleCollectionFactoryMock = $this->createPartialMock(RuleCollectionFactory::class, ['create']);
        $this->searchResultsFactoryMock = $this->createPartialMock(
            RuleSearchResultsInterfaceFactory::class,
            ['create']
        );
        $this->extensionAttributesJoinProcessorMock = $this->getMockForAbstractClass(JoinProcessorInterface::class);
        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->storeMock = $this->getMockForAbstractClass(StoreInterface::class);
        $this->collectionProcessorMock = $this->getMockForAbstractClass(CollectionProcessorInterface::class);
        $this->dataObjectHelperMock = $this->createPartialMock(DataObjectHelper::class, ['populateWithArray']);
        $this->dataObjectProcessorMock = $this->createPartialMock(DataObjectProcessor::class, ['buildOutputDataArray']);
        $this->storeManagerMock->expects($this->any())
            ->method('getStore')
            ->willReturn($this->storeMock);
        $this->storeMock->expects($this->any())
            ->method('getId')
            ->willReturn($storeId);
        $this->model = $objectManager->getObject(
            RuleRepository::class,
            [
                'resource' => $this->resourceMock,
                'ruleInterfaceFactory' => $this->ruleInterfaceFactoryMock,
                'ruleCollectionFactory' => $this->ruleCollectionFactoryMock,
                'searchResultsFactory' => $this->searchResultsFactoryMock,
                'extensionAttributesJoinProcessor' => $this->extensionAttributesJoinProcessorMock,
                'collectionProcessor' => $this->collectionProcessorMock,
                'dataObjectHelper' => $this->dataObjectHelperMock,
                'dataObjectProcessor' => $this->dataObjectProcessorMock,
                'storeManager' => $this->storeManagerMock
            ]
        );
    }

    /**
     * Testing of save method
     */
    public function testSave()
    {
        /** @var RuleInterface|\PHPUnit_Framework_MockObject_MockObject $ruleMock */
        $ruleMock = $this->createPartialMock(Rule::class, ['getRuleId']);
        $this->resourceMock->expects($this->once())
            ->method('save')
            ->willReturnSelf();
        $ruleMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn($this->ruleData['rule_id']);

        $this->assertSame($ruleMock, $this->model->save($ruleMock));
    }

    /**
     * Testing of save method on exception
     *
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     * @expectedExceptionMessage Exception message.
     */
    public function testSaveOnException()
    {
        $exception = new \Exception('Exception message.');

        /** @var RuleInterface|\PHPUnit_Framework_MockObject_MockObject $ruleMock */
        $ruleMock = $this->createPartialMock(Rule::class, ['getRuleId']);
        $this->resourceMock->expects($this->once())
            ->method('save')
            ->willThrowException($exception);

        $this->model->save($ruleMock);
    }

    /**
     * Testing of get method
     */
    public function testGet()
    {
        $ruleId = 1;
        /** @var RuleInterface|\PHPUnit_Framework_MockObject_MockObject $ruleMock */
        $ruleMock = $this->createMock(Rule::class);
        $this->ruleInterfaceFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($ruleMock);

        $this->resourceMock->expects($this->once())
            ->method('load')
            ->with($ruleMock, $ruleId)
            ->willReturnSelf();
        $ruleMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn($ruleId);

        $this->assertSame($ruleMock, $this->model->get($ruleId));
    }

    /**
     * Testing of get method on exception
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage No such entity with rule_id = 20
     */
    public function testGetOnException()
    {
        $ruleId = 20;
        $ruleMock = $this->createMock(Rule::class);
        $this->ruleInterfaceFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($ruleMock);

        $this->resourceMock->expects($this->once())
            ->method('load')
            ->with($ruleMock, $ruleId)
            ->willReturn(null);

        $this->model->get($ruleId);
    }

    /**
     * Testing of getList method
     */
    public function testGetList()
    {
        $collectionSize = 1;
        $storeId = 3;

        /** @var RuleCollection|\PHPUnit_Framework_MockObject_MockObject $ruleCollectionMock */
        $ruleCollectionMock = $this->createPartialMock(
            RuleCollection::class,
            ['getSize', 'getItems', 'setStoreId', 'addAttributeToSelect']
        );
        /** @var SearchCriteriaInterface|\PHPUnit_Framework_MockObject_MockObject $searchCriteriaMock */
        $searchCriteriaMock = $this->getMockForAbstractClass(SearchCriteriaInterface::class);
        $searchResultsMock = $this->getMockForAbstractClass(RuleSearchResultsInterface::class);
        /** @var Rule|\PHPUnit_Framework_MockObject_MockObject $ruleModelMock */
        $ruleModelMock = $this->createPartialMock(Rule::class, ['getData']);
        /** @var RuleInterface|\PHPUnit_Framework_MockObject_MockObject $ruleMock */
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);

        $this->ruleCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($ruleCollectionMock);
        $this->extensionAttributesJoinProcessorMock->expects($this->once())
            ->method('process')
            ->with($ruleCollectionMock, RuleInterface::class);
        $this->collectionProcessorMock->expects($this->once())
            ->method('process')
            ->with($searchCriteriaMock, $ruleCollectionMock);

        $ruleCollectionMock->expects($this->once())
            ->method('getSize')
            ->willReturn($collectionSize);
        $ruleCollectionMock->expects($this->once())
            ->method('setStoreId')
            ->with($storeId)
            ->willReturnSelf();
        $ruleCollectionMock->expects($this->once())
            ->method('addAttributeToSelect')
            ->willReturnSelf();

        $this->searchResultsFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($searchResultsMock);
        $searchResultsMock->expects($this->once())
            ->method('setSearchCriteria')
            ->with($searchCriteriaMock);
        $searchResultsMock->expects($this->once())
            ->method('setTotalCount')
            ->with($collectionSize);

        $ruleCollectionMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$ruleModelMock]);

        $this->ruleInterfaceFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($ruleMock);
        $this->dataObjectProcessorMock->expects($this->once())
            ->method('buildOutputDataArray')
            ->with($ruleModelMock, RuleInterface::class)
            ->willReturn($this->ruleData);
        $this->dataObjectHelperMock->expects($this->once())
            ->method('populateWithArray')
            ->with($ruleMock, $this->ruleData, RuleInterface::class);

        $searchResultsMock->expects($this->once())
            ->method('setItems')
            ->with([$ruleMock])
            ->willReturnSelf();

        $this->assertSame($searchResultsMock, $this->model->getList($searchCriteriaMock, $storeId));
    }

    /**
     * Testing of getList method
     */
    public function testDeleteById()
    {
        $ruleId = '123';

        $ruleMock = $this->createMock(Rule::class);
        $ruleMock->expects($this->any())
            ->method('getRuleId')
            ->willReturn($ruleId);
        $this->ruleInterfaceFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($ruleMock);
        $this->resourceMock->expects($this->once())
            ->method('load')
            ->with($ruleMock, $ruleId)
            ->willReturnSelf();
        $this->resourceMock->expects($this->once())
            ->method('delete')
            ->with($ruleMock)
            ->willReturn(true);

        $this->assertTrue($this->model->deleteById($ruleId));
    }

    /**
     * Testing of delete method on exception
     *
     * @expectedException \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function testDeleteException()
    {
        $ruleMock = $this->createMock(Rule::class);
        $this->resourceMock->expects($this->once())
            ->method('delete')
            ->with($ruleMock)
            ->willThrowException(new \Exception());
        $this->model->delete($ruleMock);
    }
}
