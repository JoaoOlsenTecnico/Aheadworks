<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Converter;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterfaceFactory;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor\Pool;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Framework\Api\DataObjectHelper;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor\Popup as PopupProcessor;

/**
 * Class ToMetadataRuleTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Converter
 */
class ToMetadataRuleTest extends TestCase
{
    /**
     * @var ToMetadataRule
     */
    private $model;

    /**
     * @var Pool|\PHPUnit_Framework_MockObject_MockObject
     */
    private $processorPoolMock;

    /**
     * @var RuleMetadataInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleMetadataFactoryMock;

    /**
     * @var DataObjectHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataObjectHelperMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->processorPoolMock = $this->createPartialMock(Pool::class, ['getProcessor']);
        $this->ruleMetadataFactoryMock = $this->createPartialMock(RuleMetadataInterfaceFactory::class, ['create']);
        $this->dataObjectHelperMock = $this->createPartialMock(DataObjectHelper::class, []);
        $this->model = $objectManager->getObject(
            ToMetadataRule::class,
            [
                'processorPool' => $this->processorPoolMock,
                'ruleMetadataFactory' => $this->ruleMetadataFactoryMock,
                'dataObjectHelper' =>  $this->dataObjectHelperMock
            ]
        );
    }

    /**
     * Test for convert method
     *
     * @throws \Exception
     */
    public function testConvert()
    {
        $processorType = Pool::POPUP_PROCESSOR;
        $itemMock1 = $this->getAbstractItemMock();
        $itemMock1->expects($this->once())
            ->method('setAwAfptcId')
            ->willReturnSelf();
        $itemMock1->expects($this->once())
            ->method('getHasChildren')
            ->willReturn(false);

        $itemMock2 = $this->getAbstractItemMock();
        $itemMock2->expects($this->once())
            ->method('setAwAfptcId')
            ->willReturnSelf();
        $itemMock2->expects($this->once())
            ->method('getHasChildren')
            ->willReturn(true);
        $itemMock2->expects($this->once())
            ->method('isChildrenCalculated')
            ->willReturn(true);
        $childItemMock = $this->getAbstractItemMock();
        $itemMock2->expects($this->once())
            ->method('getChildren')
            ->willReturn([$childItemMock]);
        $childItemMock->expects($this->once())
            ->method('setAwAfptcId')
            ->willReturnSelf();

        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $ruleMetadataObjectMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $this->ruleMetadataFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($ruleMetadataObjectMock);
        $ruleMetadataObjectMock->expects($this->once())
            ->method('setRule')
            ->with($ruleMock)
            ->willReturnSelf();
        $processor = $this->createPartialMock(PopupProcessor::class, ['prepareData']);
        $this->processorPoolMock->expects($this->once())
            ->method('getProcessor')
            ->with($processorType)
            ->willReturn($processor);

        $processor->expects($this->once())
            ->method('prepareData')
            ->with($ruleMetadataObjectMock, [$itemMock1, $itemMock2], []);

        $ruleMetadataObjectMock->expects($this->once())
            ->method('getPromoProducts')
            ->willReturn(['sku' => '1234test']);

        $this->assertEquals(
            [$ruleMetadataObjectMock],
            $this->model->convert([$ruleMock], [$itemMock1, $itemMock2], $processorType)
        );
    }

    /**
     * Get abstract mock of abstract item
     *
     * @return AbstractItem|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getAbstractItemMock()
    {
        $itemMock = $this->getMockForAbstractClass(
            AbstractItem::class,
            [],
            '',
            false,
            false,
            true,
            ['setAwAfptcId', 'getHasChildren', 'isChildrenCalculated', 'getChildren']
        );

        return $itemMock;
    }
}
