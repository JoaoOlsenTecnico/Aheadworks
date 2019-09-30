<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Cart\Item\Renderer;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Api\RuleRepositoryInterface;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Pool as CalculatorPool;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterfaceFactory;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Cart\Item\Renderer\PriceAdjustment;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\DiscountCalculatorInterface;

/**
 * Class PriceAdjustmentTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Cart\Item\Renderer
 */
class PriceAdjustmentTest extends TestCase
{
    /**
     * @var PriceAdjustment
     */
    private $model;

    /**
     * @var RuleRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleRepositoryMock;

    /**
     * @var CalculatorPool|\PHPUnit_Framework_MockObject_MockObject
     */
    private $calculatorPoolMock;

    /**
     * @var RuleMetadataInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleMetadataFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->ruleRepositoryMock = $this->getMockForAbstractClass(RuleRepositoryInterface::class);
        $this->calculatorPoolMock = $this->createMock(CalculatorPool::class);
        $this->ruleMetadataFactoryMock = $this->createPartialMock(RuleMetadataInterfaceFactory::class, ['create']);
        $this->model = $objectManager->getObject(
            PriceAdjustment::class,
            [
                'ruleRepository' => $this->ruleRepositoryMock,
                'calculatorPool' => $this->calculatorPoolMock,
                'ruleMetadataFactory' => $this->ruleMetadataFactoryMock,

            ]
        );
    }

    /**
     * Test adjustForRendering method with promo product
     */
    public function testAdjustForRenderingWithPromoProduct()
    {
        $appliedRules = '1,2,3';
        $firstRule = '1';
        $discountType = 'percent';
        $someDiscount = '50';

        $quoteItemMock = $this->getMockBuilder(AbstractItem::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getAwAfptcIsPromo',
                    'getAwAfptcRuleIds',
                    'setPriceInclTax',
                    'setBasePriceInclTax',
                    'setRowTotalInclTax',
                    'setBaseRowTotalInclTax',
                    'setCalculationPrice',
                    'setPrice',
                    'setBasePrice',
                    'setRowTotal',
                    'setBaseRowTotal',
                    'getCalculationPrice'
                ]
            )->getMockForAbstractClass();

        $quoteItemMock->expects($this->once())
            ->method('getAwAfptcIsPromo')
            ->willReturn(true);
        $quoteItemMock->expects($this->once())
            ->method('getAwAfptcRuleIds')
            ->willReturn($appliedRules);

        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $ruleMock->expects($this->once())
        ->method('getDiscountType')
        ->willReturn($discountType);
        $this->ruleRepositoryMock->expects($this->once())
            ->method('get')
            ->with($firstRule)
            ->willReturn($ruleMock);
        $ruleMetadataMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $this->ruleMetadataFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($ruleMetadataMock);
        $ruleMetadataMock->expects($this->once())
            ->method('setRule')
            ->with($ruleMock)
            ->willReturnSelf();
        $calculator = $this->getMockForAbstractClass(DiscountCalculatorInterface::class);
        $this->calculatorPoolMock->expects($this->once())
            ->method('getCalculatorByType')
            ->with($discountType)
            ->willReturn($calculator);
        $calculator->expects($this->any())
            ->method('calculatePrice')
            ->withAnyParameters()
            ->willReturn($someDiscount);

        $quoteItemMock->expects($this->once())
            ->method('setPriceInclTax')
            ->with($someDiscount)
            ->willReturnSelf();
        $quoteItemMock->expects($this->once())
            ->method('setBasePriceInclTax')
            ->with($someDiscount)
            ->willReturnSelf();
        $quoteItemMock->expects($this->once())
            ->method('setRowTotalInclTax')
            ->with($someDiscount)
            ->willReturnSelf();
        $quoteItemMock->expects($this->once())
            ->method('setBaseRowTotalInclTax')
            ->with($someDiscount)
            ->willReturnSelf();
        $quoteItemMock->expects($this->once())
            ->method('setCalculationPrice')
            ->with($someDiscount)
            ->willReturnSelf();
        $quoteItemMock->expects($this->once())
            ->method('setPrice')
            ->with($someDiscount)
            ->willReturnSelf();
        $quoteItemMock->expects($this->once())
            ->method('getCalculationPrice')
            ->willReturn($someDiscount);
        $quoteItemMock->expects($this->once())
            ->method('setBasePrice')
            ->with($someDiscount)
            ->willReturnSelf();
        $quoteItemMock->expects($this->once())
            ->method('setRowTotal')
            ->with($someDiscount)
            ->willReturnSelf();
        $quoteItemMock->expects($this->once())
            ->method('setBaseRowTotal')
            ->with($someDiscount)
            ->willReturnSelf();

        $this->model->adjustForRendering($quoteItemMock);
    }

    /**
     * Test adjustForRendering method with regular product
     */
    public function testAdjustForRenderingWithRegularProduct()
    {
        $quoteItemMock = $this->getMockBuilder(AbstractItem::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAwAfptcIsPromo'])
            ->getMockForAbstractClass();

        $quoteItemMock->expects($this->once())
            ->method('getAwAfptcIsPromo')
            ->willReturn(false);

        $this->model->adjustForRendering($quoteItemMock);
    }

    /**
     * Test adjustForRendering method without promo rule set
     */
    public function testAdjustForRenderingWithNoSetRule()
    {
        $appliedRules = null;
        $quoteItemMock = $this->getMockBuilder(AbstractItem::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAwAfptcIsPromo', 'getAwAfptcRuleIds'])
            ->getMockForAbstractClass();

        $quoteItemMock->expects($this->once())
            ->method('getAwAfptcIsPromo')
            ->willReturn(true);

        $quoteItemMock->expects($this->once())
            ->method('getAwAfptcRuleIds')
            ->willReturn($appliedRules);

        $this->model->adjustForRendering($quoteItemMock);
    }
}
