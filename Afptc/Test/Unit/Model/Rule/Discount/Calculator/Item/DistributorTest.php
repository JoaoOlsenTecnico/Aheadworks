<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace  Aheadworks\Afptc\Test\Unit\Model\Rule\Discount\Calculator\Item;

use Aheadworks\Afptc\Model\Metadata\Rule\Discount\Item as MetadataRuleDiscountItem;
use Aheadworks\Afptc\Model\Metadata\Rule\Discount\ItemFactory as MetadataRuleDiscountItemFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Api\SimpleDataObjectConverter;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Distributor;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Processor;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class DistributorTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Discount\Calculator\Item
 */
class DistributorTest extends TestCase
{
    /**
     * @var Distributor
     */
    private $model;

    /**
     * @var MetadataRuleDiscountItemFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $metadataRuleDiscountItemFactoryMock;

    /**
     * @var SimpleDataObjectConverter|\PHPUnit_Framework_MockObject_MockObject
     */
    private $simpleDataObjectConverterMock;

    /**
     * @var PriceCurrencyInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $priceCurrencyMock;

    /**
     * @var Processor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $processorMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->metadataRuleDiscountItemFactoryMock = $this->createPartialMock(
            MetadataRuleDiscountItemFactory::class,
            ['create']
        );
        $this->simpleDataObjectConverterMock = $this->createPartialMock(
            SimpleDataObjectConverter::class,
            ['snakeCaseToUpperCamelCase']
        );
        $this->priceCurrencyMock = $this->getMockForAbstractClass(PriceCurrencyInterface::class);
        $this->processorMock = $this->createPartialMock(
            Processor::class,
            ['getTotalItemBasePrice']
        );
        $this->model = $objectManager->getObject(
            Distributor::class,
            [
                'metadataRuleDiscountItemFactory' => $this->metadataRuleDiscountItemFactoryMock,
                'simpleDataObjectConverter' => $this->simpleDataObjectConverterMock,
                'priceCurrency' => $this->priceCurrencyMock,
                'processor' => $this->processorMock
            ]
        );
    }

    /**
     * Test for distribute method
     */
    public function testDistribute()
    {
        $metadataRuleDiscountItemMock = $this->createPartialMock(
            MetadataRuleDiscountItem::class,
            ['getItem', 'getQty', 'setAmount', 'setBaseAmount', 'setChildren']
        );
        $itemMock = $this->createPartialMock(
            AbstractItem::class,
            [
                'getAddress',
                'getQuote',
                'getOptionByCode',
                'getHasChildren',
                'isChildrenCalculated',
                'getChildren'
            ]
        );

        $metadataRuleDiscountItemMock->expects($this->exactly(2))
            ->method('getItem')
            ->willReturn($itemMock);
        $itemMock->expects($this->once())
            ->method('getHasChildren')
            ->willReturn(true);
        $itemMock->expects($this->once())
            ->method('isChildrenCalculated')
            ->willReturn(true);
        $qtyToDiscount = 5;
        $metadataRuleDiscountItemMock->expects($this->once())
            ->method('getQty')
            ->willReturn($qtyToDiscount);
        $parentBaseTotal = 10;
        $this->processorMock->expects($this->once())
            ->method('getTotalItemBasePrice')
            ->with($itemMock, $qtyToDiscount)
            ->willReturn($parentBaseTotal);

        $itemMock->expects($this->once())
            ->method('getChildren')
            ->willReturn([]);

        $metadataRuleDiscountItemMock->expects($this->once())
            ->method('setAmount')
            ->with(0)
            ->willReturnSelf();
        $metadataRuleDiscountItemMock->expects($this->once())
            ->method('setBaseAmount')
            ->with(0)
            ->willReturnSelf();
        $metadataRuleDiscountItemMock->expects($this->once())
            ->method('setChildren')
            ->with([])
            ->willReturnSelf();

        $this->assertEquals(
            $metadataRuleDiscountItemMock,
            $this->model->distribute($metadataRuleDiscountItemMock)
        );
    }
}
