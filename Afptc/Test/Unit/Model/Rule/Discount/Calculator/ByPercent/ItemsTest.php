<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Discount\Calculator\ByPercent;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Processor;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Validator;
use Aheadworks\Afptc\Model\Rule\RuleMetadataManager;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\ByPercent\Items;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Metadata\Rule\Discount as MetadataRuleDiscount;
use Aheadworks\Afptc\Model\Metadata\Rule\Discount\Item as DiscountItem;
use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class ItemsTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Discount\Calculator\ByPercent
 */
class ItemsTest extends TestCase
{
    /**
     * @var Items
     */
    private $model;

    /**
     * @var Validator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $validatorMock;

    /**
     * @var Processor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $processorMock;

    /**
     * @var RuleMetadataManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleMetadataManagerMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->validatorMock = $this->createPartialMock(
            Validator::class,
            ['canApplyDiscount']
        );
        $this->processorMock = $this->createPartialMock(
            Processor::class,
            ['getTotalItemPrice', 'getTotalItemBasePrice']
        );
        $this->ruleMetadataManagerMock = $this->createPartialMock(
            RuleMetadataManager::class,
            ['getPromoProductQty']
        );
        $this->model = $objectManager->getObject(
            Items::class,
            [
                'validator' => $this->validatorMock,
                'processor' => $this->processorMock,
                'ruleMetadataManager' => $this->ruleMetadataManagerMock
            ]
        );
    }

    /**
     * Test for calculate method
     */
    public function testCalculate()
    {
        $itemMock1 = $this->getMockForAbstractClass(
            AbstractItem::class,
            [],
            '',
            false,
            false,
            true,
            ['getAwAfptcId']
        );
        $itemMock2 = $this->getMockForAbstractClass(
            AbstractItem::class,
            [],
            '',
            false,
            false,
            true,
            []
        );
        $id = 3;
        $itemMock1->expects($this->once())
            ->method('getAwAfptcId')
            ->willReturn($id);

        $metadataRuleMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $this->validatorMock->expects($this->exactly(2))
            ->method('canApplyDiscount')
            ->withConsecutive([$itemMock1, $metadataRuleMock], [$itemMock2, $metadataRuleMock])
            ->willReturnOnConsecutiveCalls(true, false);

        $discountItemMock = $this->createPartialMock(
            DiscountItem::class,
            ['addAmount', 'addBaseAmount', 'addQtyByRule', 'addPercent', 'setItem']
        );
        $metadataRuleDiscountMock = $this->createPartialMock(MetadataRuleDiscount::class, ['getItemById']);
        $metadataRuleDiscountMock->expects($this->once())
            ->method('getItemById')
            ->with($id, true)
            ->willReturn($discountItemMock);
        $qtyToDiscount = 3;
        $this->ruleMetadataManagerMock->expects($this->once())
            ->method('getPromoProductQty')
            ->with($metadataRuleMock, $id)
            ->willReturn($qtyToDiscount);
        $price = 12;
        $basePrice = 12;
        $this->processorMock->expects($this->once())
            ->method('getTotalItemPrice')
            ->with($itemMock1, $qtyToDiscount)
            ->willReturn($price);
        $this->processorMock->expects($this->once())
            ->method('getTotalItemBasePrice')
            ->with($itemMock1, $qtyToDiscount)
            ->willReturn($basePrice);
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $ruleMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn(1);
        $metadataRuleMock->expects($this->exactly(4))
            ->method('getRule')
            ->willReturn($ruleMock);
        $ruleMock->expects($this->exactly(3))
            ->method('getDiscountAmount')
            ->willReturn(2);
        $discountItemMock->expects($this->once())
            ->method('addAmount')
            ->willReturnSelf();
        $discountItemMock->expects($this->once())
            ->method('addBaseAmount')
            ->willReturnSelf();
        $discountItemMock->expects($this->once())
            ->method('addPercent')
            ->willReturnSelf();
        $discountItemMock->expects($this->once())
            ->method('addQtyByRule')
            ->willReturnSelf();
        $discountItemMock->expects($this->once())
            ->method('setItem')
            ->with($itemMock1)
            ->willReturnSelf();

        $this->model->calculate([$itemMock1, $itemMock2], $metadataRuleMock, $metadataRuleDiscountMock);
    }
}
