<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Discount;

use Aheadworks\Afptc\Model\Metadata\Rule\Discount as MetadataRuleDiscount;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory;
use Magento\Quote\Api\Data\CartItemExtensionInterface;
use Aheadworks\Afptc\Model\Rule\Discount\ItemsApplier;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Quote\Model\Quote\Address;

/**
 * Class ItemsApplierTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Discount
 */
class ItemsApplierTest extends TestCase
{
    /**
     * @var ItemsApplier
     */
    private $model;

    /**
     * @var CartItemExtensionInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartItemExtensionFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->cartItemExtensionFactoryMock = $this->createPartialMock(
            CartItemExtensionInterfaceFactory::class,
            ['create']
        );
        $this->model = $objectManager->getObject(
            ItemsApplier::class,
            [
                'cartItemExtensionFactory' => $this->cartItemExtensionFactoryMock
            ]
        );
    }

    /**
     * Test for reset method
     */
    public function testReset()
    {
        $itemMock = $this->prepareItem();
        $this->model->reset($itemMock);
    }

    /**
     * Test for apply method
     */
    public function testApply()
    {
        $itemMock1 = $this->createPartialMock(
            AbstractItem::class,
            [
                'getAddress',
                'getQuote',
                'getOptionByCode',
                'getParentItem'
            ]
        );
        $itemMock1->expects($this->once())
            ->method('getParentItem')
            ->willReturn(true);
        $itemMock2 = $this->prepareItem();
        $itemMock2->expects($this->once())
            ->method('getParentItem')
            ->willReturn(false);

        $metaDataRuleDiscount = $this->createPartialMock(
            MetadataRuleDiscount::class,
            ['getItemById']
        );

        $this->model->apply([$itemMock1, $itemMock2], $metaDataRuleDiscount);
    }

    /**
     * Prepare item
     *
     * @return AbstractItem|\PHPUnit_Framework_MockObject_MockObject
     */
    private function prepareItem()
    {
        /** @var AbstractItem|\PHPUnit_Framework_MockObject_MockObject $itemMock */
        $itemMock = $this->createPartialMock(
            AbstractItem::class,
            [
                'setAwAfptcAmount',
                'setBaseAwAfptcAmount',
                'setAwAfptcPercent',
                'setAwAfptcRuleIds',
                'setAwAfptcQty',
                'setExtensionAttributes',
                'getAddress',
                'getHasChildren',
                'isChildrenCalculated',
                'getChildren',
                'getQuote',
                'getOptionByCode',
                'getExtensionAttributes',
                'getParentItem'
            ]
        );

        $extensionAttributes = $this->getMockForAbstractClass(
            CartItemExtensionInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['setAwAfptcRules']
        );
        $itemMock->expects($this->exactly(2))
            ->method('getExtensionAttributes')
            ->willReturn($extensionAttributes);
        $extensionAttributes->expects($this->once())
            ->method('setAwAfptcRules')
            ->with([])
            ->willReturnSelf();

        $itemMock->expects($this->once())
            ->method('setAwAfptcAmount')
            ->with(0)
            ->willReturnSelf();
        $itemMock->expects($this->once())
            ->method('setBaseAwAfptcAmount')
            ->with(0)
            ->willReturnSelf();
        $itemMock->expects($this->once())
            ->method('setAwAfptcPercent')
            ->with(null)
            ->willReturnSelf();
        $itemMock->expects($this->once())
            ->method('setAwAfptcRuleIds')
            ->with(null)
            ->willReturnSelf();
        $itemMock->expects($this->once())
            ->method('setAwAfptcQty')
            ->with(null)
            ->willReturnSelf();
        $itemMock->expects($this->once())
            ->method('setAwAfptcQty')
            ->with(null)
            ->willReturnSelf();
        $itemMock->expects($this->once())
            ->method('setExtensionAttributes')
            ->with($extensionAttributes)
            ->willReturnSelf();

        $addressMock = $this->createPartialMock(Address::class, ['setAwAfptcRuleIds']);
        $itemMock->expects($this->once())
            ->method('getAddress')
            ->willReturn($addressMock);
        $addressMock->expects($this->once())
            ->method('setAwAfptcRuleIds')
            ->with(null)
            ->willReturnSelf();
        $itemMock->expects($this->once())
            ->method('getHasChildren')
            ->willReturn(false);

        return $itemMock;
    }
}
