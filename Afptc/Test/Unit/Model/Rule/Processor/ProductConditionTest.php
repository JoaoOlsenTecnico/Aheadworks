<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Processor;

use Aheadworks\Afptc\Model\Rule\Processor\ProductCondition;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\Rule\Converter\Condition as ConditionConverter;
use Aheadworks\Afptc\Api\Data\ConditionInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class ProductConditionTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Processor
 */
class ProductConditionTest extends TestCase
{
    /**
     * @var ProductCondition
     */
    private $model;

    /**
     * @var ConditionConverter|\PHPUnit_Framework_MockObject_MockObject
     */
    private $conditionConverterMock;

    /**
     * @var Json|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serializerMock;

    /**
     * @var array
     */
    private $testConditionArray = [
        'value' => 10,
        'aggregator' => 'all'
    ];

    /**
     * @var string
     */
    private $serializedConditions = ['value:10,aggregator:all'];

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->conditionConverterMock = $this->createPartialMock(
            ConditionConverter::class,
            ['arrayToDataModel', 'dataModelToArray']
        );
        $this->serializerMock = $this->createPartialMock(
            Json::class,
            ['serialize', 'unserialize']
        );

        $this->model = $objectManager->getObject(
            ProductCondition::class,
            [
                'conditionConverter' => $this->conditionConverterMock,
                'serializer' => $this->serializerMock
            ]
        );
    }

    /**
     * Test for beforeSave method
     */
    public function testBeforeSave()
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $conditionMock = $this->getMockForAbstractClass(ConditionInterface::class);
        $ruleMock->expects($this->exactly(2))
            ->method('getCartConditions')
            ->willReturn($conditionMock);
        $this->conditionConverterMock->expects($this->once())
            ->method('dataModelToArray')
            ->with($conditionMock)
            ->willReturn($this->testConditionArray);

        $this->serializerMock->expects($this->once())
            ->method('serialize')
            ->with($this->testConditionArray)
            ->willReturn($this->serializedConditions);

        $ruleMock->expects($this->once())
            ->method('setCartConditions')
            ->with($this->serializedConditions)
            ->willReturnSelf();

        $this->assertSame($ruleMock, $this->model->beforeSave($ruleMock));
    }

    /**
     * Test for afterLoad method
     */
    public function testAfterLoad()
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $conditionMock = $this->getMockForAbstractClass(ConditionInterface::class);
        $ruleMock->expects($this->exactly(2))
            ->method('getCartConditions')
            ->willReturn($this->serializedConditions);
        $this->serializerMock->expects($this->once())
            ->method('unserialize')
            ->with($this->serializedConditions)
            ->willReturn($this->testConditionArray);
        $this->conditionConverterMock->expects($this->once())
            ->method('arrayToDataModel')
            ->with($this->testConditionArray)
            ->willReturn($conditionMock);
        $ruleMock->expects($this->once())
            ->method('setCartConditions')
            ->with($conditionMock)
            ->willReturnSelf();

        $this->assertSame($ruleMock, $this->model->afterLoad($ruleMock));
    }
}
