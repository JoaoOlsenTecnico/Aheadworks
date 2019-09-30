<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Cart;

use Aheadworks\Afptc\Model\Cart\Cart;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class CartTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Cart
 */
class CartTest extends TestCase
{
    /**
     * @var Cart
     */
    private $model;

    /**
     * @var CartRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartRepositoryMock;

    /**
     * @var RuleManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleManagementMock;

    /**
     * @var Quote|\PHPUnit_Framework_MockObject_MockObject
     */
    private $quoteMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->cartRepositoryMock = $this->getMockForAbstractClass(CartRepositoryInterface::class);
        $this->ruleManagementMock = $this->getMockForAbstractClass(RuleManagementInterface::class);
        $this->model = $objectManager->getObject(
            Cart::class,
            [
                'cartRepository' => $this->cartRepositoryMock,
                'ruleManagement' => $this->ruleManagementMock
            ]
        );

        $this->quoteMock = $this->createPartialMock(
            Quote::class,
            [
                'getId',
                'getItems',
                'setItems',
                'collectTotals',
                'getAllItems',
                'removeItem',
                'getCouponCode',
                'setCouponCode',
                'setAwAfptcUsesCoupon'
            ]
        );
    }

    /**
     * Test addProductsToCart method
     */
    public function testAddProductsToCart()
    {
        $newCartItems = [$this->getMockForAbstractClass(CartItemInterface::class)];
        $quoteItems = [$this->getMockForAbstractClass(CartItemInterface::class)];
        $mergedItems = array_merge($newCartItems, $quoteItems);

        $this->quoteMock->expects($this->once())
            ->method('getAllItems')
            ->willReturn($quoteItems);
        $this->quoteMock->expects($this->once())
            ->method('setItems')
            ->with($mergedItems)
            ->willReturnSelf();
        $this->cartRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->quoteMock)
            ->willReturn($this->quoteMock);
        $this->quoteMock->expects($this->once())
            ->method('collectTotals')
            ->willReturnSelf();

        $this->assertEquals($this->quoteMock, $this->model->addProductsToCart($this->quoteMock, $newCartItems));
    }

    /**
     * Test removeUnusedPromoData method
     *
     * @dataProvider removeUnusedItemsDataProvider
     */
    public function testRemoveUnusedItems($expected, $afptcQty, $qty, $awAfptcIsPromo)
    {
        $cartItemId = 1;
        $cartItemMock = $this->getMockForAbstractClass(
            CartItemInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getAwAfptcQty', 'getAwAfptcIsPromo', 'getId']
        );
        $quoteItems = [$cartItemMock];

        $this->quoteMock->expects($this->once())
            ->method('getAllItems')
            ->willReturn($quoteItems);

        $cartItemMock->expects($this->once())
            ->method('getAwAfptcQty')
            ->willReturn($afptcQty);
        $cartItemMock->expects($this->once())
            ->method('getQty')
            ->willReturn($qty);
        $cartItemMock->expects($this->once())
            ->method('getAwAfptcIsPromo')
            ->willReturn($awAfptcIsPromo);

        if ($awAfptcIsPromo) {
            if ($afptcQty == 0) {
                $cartItemMock->expects($this->once())
                    ->method('getId')
                    ->willReturn($cartItemId);
                $this->quoteMock->expects($this->once())
                    ->method('removeItem')
                    ->with($cartItemId)
                    ->willReturnSelf();
            } elseif ($qty > $afptcQty) {
                $cartItemMock->expects($this->once())
                    ->method('setQty')
                    ->with($afptcQty)
                    ->willReturnSelf();
            }
        }

        $this->quoteMock->expects($this->once())
            ->method('getCouponCode')
            ->willReturn(null);

        $expected = $expected ? $this->quoteMock : null;
        $this->assertEquals($expected, $this->model->removeUnusedPromoData($this->quoteMock));
    }

    /**
     * Data provider for removeUnusedItems test
     *
     * @return array
     */
    public function removeUnusedItemsDataProvider()
    {
        return [
            [true, 0, 0, true],
            [false, 1, 1, true],
            [true, 1, 2, true],
            [false, 1, null, false]
        ];
    }

    /**
     * Test removeUnusedPromoData method
     *
     * @dataProvider removeUnusedCouponDataProvider
     */
    public function testRemoveUnusedCoupon($expected)
    {
        $quoteItems = [];
        $couponCode = 'code';
        $quoteId = 1;
        $this->quoteMock->expects($this->once())
            ->method('getAllItems')
            ->willReturn($quoteItems);
        $this->quoteMock->expects($this->once())
            ->method('getId')
            ->willReturn($quoteId);

        $this->quoteMock->expects($this->once())
            ->method('getCouponCode')
            ->willReturn($couponCode);
        $this->ruleManagementMock->expects($this->once())
            ->method('isValidCoupon')
            ->with($couponCode, $quoteId)
            ->willReturn($expected);

        if ($expected === false) {
            $this->quoteMock->expects($this->once())
                ->method('setCouponCode')
                ->with('')
                ->willReturnSelf();
            $this->quoteMock->expects($this->once())
                ->method('setAwAfptcUsesCoupon')
                ->with(null)
                ->willReturnSelf();
        }

        $expected = $expected === false ? $this->quoteMock : null;
        $this->assertEquals($expected, $this->model->removeUnusedPromoData($this->quoteMock));
    }

    /**
     * Data provider for removeUnusedCoupon test
     *
     * @return array
     */
    public function removeUnusedCouponDataProvider()
    {
        return [
            [true],
            [false],
            [null]
        ];
    }
}
