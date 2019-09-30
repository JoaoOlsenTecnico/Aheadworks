<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace  Aheadworks\Afptc\Test\Unit\Model\Rule\Discount\Calculator\Item;

use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Processor;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class ProcessorTest
 *
 * @package Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item
 */
class ProcessorTest extends TestCase
{
    /**
     * @var Processor
     */
    private $model;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(Processor::class, []);
    }

    /**
     * Test for getTotalItemPrice method
     *
     * @dataProvider getTotalItemPriceDataProvider
     * @param int|null $qty
     * @param bool $hasChildren
     * @param bool $isChildrenCalculated
     * @param int|float $result
     */
    public function testGetTotalItemPrice($qty, $hasChildren, $isChildrenCalculated, $result)
    {
        /** @var AbstractItem|\PHPUnit_Framework_MockObject_MockObject $itemMock */
        $itemMock = $this->createPartialMock(
            AbstractItem::class,
            [
                'getAddress',
                'getQuote',
                'getOptionByCode',
                'getTotalQty',
                'getHasChildren',
                'isChildrenCalculated',
                'getChildren',
                'getAwRewardPointsAmount',
                'getAwRafAmount',
                'getDiscountAmount',
                'getDiscountCalculationPrice',
                'getCalculationPrice'
            ]
        );

        if (!$qty) {
            $itemMock->expects($this->once())
                ->method('getTotalQty')
                ->willReturn(5);
        }

        $itemMock->expects($this->once())
            ->method('getHasChildren')
            ->willReturn($hasChildren);
        $itemMock->expects($this->any())
            ->method('isChildrenCalculated')
            ->willReturn($isChildrenCalculated);

        $itemMock->expects($this->once())
            ->method('getDiscountCalculationPrice')
            ->willReturn(30);
        $itemMock->expects($this->once())
            ->method('getCalculationPrice')
            ->willReturn(25);

        if ($hasChildren && $isChildrenCalculated) {
            $childItem = clone $itemMock;
            $itemMock->expects($this->once())
                ->method('getChildren')
                ->willReturn([$childItem]);

            $childItem->expects($this->once())
                ->method('getDiscountAmount')
                ->willReturn(8);
        } else {
            $itemMock->expects($this->once())
                ->method('getDiscountAmount')
                ->willReturn(7);
        }

        $itemMock->expects($this->once())
            ->method('getAwRewardPointsAmount')
            ->willReturn(6);
        $itemMock->expects($this->once())
            ->method('getAwRafAmount')
            ->willReturn(4);

        $this->assertSame($result, $this->model->getTotalItemPrice($itemMock, $qty));
    }

    /**
     * Test for getBaseTotalItemPrice method
     *
     * @dataProvider getTotalItemPriceDataProvider
     * @param int|null $qty
     * @param bool $hasChildren
     * @param bool $isChildrenCalculated
     * @param int|float $result
     */
    public function testGetBaseTotalItemPrice($qty, $hasChildren, $isChildrenCalculated, $result)
    {
        /** @var AbstractItem|\PHPUnit_Framework_MockObject_MockObject $itemMock */
        $itemMock = $this->createPartialMock(
            AbstractItem::class,
            [
                'getAddress',
                'getQuote',
                'getOptionByCode',
                'getTotalQty',
                'getHasChildren',
                'isChildrenCalculated',
                'getChildren',
                'getBaseAwRewardPointsAmount',
                'getBaseAwRafAmount',
                'getBaseDiscountAmount',
                'getDiscountCalculationPrice',
                'getBaseDiscountCalculationPrice'
            ]
        );

        if (!$qty) {
            $itemMock->expects($this->once())
                ->method('getTotalQty')
                ->willReturn(5);
        }

        $itemMock->expects($this->once())
            ->method('getHasChildren')
            ->willReturn($hasChildren);
        $itemMock->expects($this->any())
            ->method('isChildrenCalculated')
            ->willReturn($isChildrenCalculated);

        $itemMock->expects($this->once())
            ->method('getDiscountCalculationPrice')
            ->willReturn(30);
        $itemMock->expects($this->once())
            ->method('getBaseDiscountCalculationPrice')
            ->willReturn(30);

        if ($hasChildren && $isChildrenCalculated) {
            $childItem = clone $itemMock;
            $itemMock->expects($this->once())
                ->method('getChildren')
                ->willReturn([$childItem]);

            $childItem->expects($this->once())
                ->method('getBaseDiscountAmount')
                ->willReturn(8);
        } else {
            $itemMock->expects($this->once())
                ->method('getBaseDiscountAmount')
                ->willReturn(7);
        }

        $itemMock->expects($this->once())
            ->method('getBaseAwRewardPointsAmount')
            ->willReturn(6);
        $itemMock->expects($this->once())
            ->method('getBaseAwRafAmount')
            ->willReturn(4);

        $this->assertSame($result, $this->model->getTotalItemBasePrice($itemMock, $qty));
    }

    /**
     * Data provider for testGetTotalItemPrice method
     */
    public function getTotalItemPriceDataProvider()
    {
        return [
            [3, true, true, 72],
            [null, true, true, 132],
            [3, false, true, 73],
            [3, true, false, 73],
            [3, false, false, 73]
        ];
    }
}
