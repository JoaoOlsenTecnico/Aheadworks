<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Discount;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Pool as CalculatorPool;
use Aheadworks\Afptc\Model\Metadata\Rule\DiscountFactory as MetadataRuleDiscountFactory;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Api\Data\AddressInterface;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Distributor;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Model\Metadata\Rule\Discount;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\Source\Rule\Discount\Type as DiscountType;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\ByPercent;
use Aheadworks\Afptc\Model\Metadata\Rule\Discount\Item;

/**
 * Class CalculatorTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Discount
 */
class CalculatorTest extends TestCase
{
    /**
     * @var Calculator
     */
    private $model;

    /**
     * @var MetadataRuleDiscountFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $metadataRuleDiscountFactoryMock;

    /**
     * @var CalculatorPool|\PHPUnit_Framework_MockObject_MockObject
     */
    private $calculatorPoolMock;

    /**
     * @var Distributor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $distributorMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->metadataRuleDiscountFactoryMock = $this->createPartialMock(
            MetadataRuleDiscountFactory::class,
            ['create']
        );
        $this->calculatorPoolMock = $this->createPartialMock(
            CalculatorPool::class,
            ['getCalculatorByType']
        );
        $this->distributorMock = $this->createPartialMock(
            Distributor::class,
            ['distribute']
        );
        $this->model = $objectManager->getObject(
            Calculator::class,
            [
                'metadataRuleDiscountFactory' => $this->metadataRuleDiscountFactoryMock,
                'calculatorPool' => $this->calculatorPoolMock,
                'distributor' => $this->distributorMock
            ]
        );
    }

    /**
     * Test for calculate discount
     */
    public function testCalculateDiscount()
    {
        $itemMock = $this->createPartialMock(
            AbstractItem::class,
            [
                'getAddress',
                'getQuote',
                'getOptionByCode',
                'getParentItem'
            ]
        );

        $addressMock = $this->getMockForAbstractClass(AddressInterface::class);
        $metaDataRuleMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $metaDataRuleMock->expects($this->once())
            ->method('getRule')
            ->willReturn($ruleMock);
        $ruleMock->expects($this->once())
            ->method('getDiscountType')
            ->willReturn(DiscountType::PERCENT);
        $byPercentCalculator = $this->createPartialMock(ByPercent::class, ['calculate']);
        $this->calculatorPoolMock->expects($this->once())
            ->method('getCalculatorByType')
            ->with(DiscountType::PERCENT)
            ->willReturn($byPercentCalculator);

        $metaDataRuleDiscount = $this->createPartialMock(Discount::class, ['getItems']);
        $this->metadataRuleDiscountFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($metaDataRuleDiscount);
        $byPercentCalculator->expects($this->once())
            ->method('calculate')
            ->with([$itemMock], $addressMock, $metaDataRuleMock, $metaDataRuleDiscount)
            ->willReturn($metaDataRuleDiscount);

        $discountItemMock = $this->createPartialMock(Item::class, []);
        $metaDataRuleDiscount->expects($this->once())
            ->method('getItems')
            ->willReturn([$discountItemMock]);
        $this->distributorMock->expects($this->once())
            ->method('distribute')
            ->with($discountItemMock)
            ->willReturn($metaDataRuleDiscount);

        $this->assertEquals(
            $metaDataRuleDiscount,
            $this->model->calculateDiscount([$itemMock], $addressMock, [$metaDataRuleMock])
        );
    }

    /**
     * Test for calculate price
     */
    public function testCalculatePrice()
    {
        $price = 20;
        $metaDataRuleMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $metaDataRuleMock->expects($this->once())
            ->method('getRule')
            ->willReturn($ruleMock);
        $ruleMock->expects($this->once())
            ->method('getDiscountType')
            ->willReturn(DiscountType::PERCENT);
        $byPercentCalculator = $this->createPartialMock(ByPercent::class, ['calculatePrice']);
        $this->calculatorPoolMock->expects($this->once())
            ->method('getCalculatorByType')
            ->with(DiscountType::PERCENT)
            ->willReturn($byPercentCalculator);

        $byPercentCalculator->expects($this->once())
            ->method('calculatePrice')
            ->with($price, $metaDataRuleMock)
            ->willReturn($price);

        $this->assertEquals(
            $price,
            $this->model->calculatePrice($price, $metaDataRuleMock)
        );
    }
}
