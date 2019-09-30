<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Service;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Service\RuleService;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\Store;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Manager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Modifier;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Cart\Area\Resolver as CartAreaResolver;

/**
 * Class RuleServiceTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Service
 */
class RuleServiceTest extends TestCase
{
    /**
     * @var RuleService
     */
    private $model;

    /**
     * @var CartRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartRepositoryMock;

    /**
     * @var Manager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleManagerMock;

    /**
     * @var Modifier|\PHPUnit_Framework_MockObject_MockObject
     */
    private $addressModifierMock;

    /**
     * @var int
     */
    private $storeId;

    /**
     * @var int
     */
    private $customerGroupId = 1;

    /**
     * @var CartItemInterface[]|AbstractItem[]|\PHPUnit_Framework_MockObject_MockObject[]
     */
    private $items;

    /**
     * @var Quote\Address|\PHPUnit_Framework_MockObject_MockObject
     */
    private $address;

    /**
     * @var CartAreaResolver|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartAreaResolverMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->cartRepositoryMock = $this->getMockForAbstractClass(CartRepositoryInterface::class);
        $this->ruleManagerMock = $this->createPartialMock(
            Manager::class,
            [
                'getAutoAddMetadataRules',
                'getPopUpMetadataRules',
                'getDiscountMetadataRules',
                'isValidCoupon'
            ]
        );
        $this->cartAreaResolverMock = $this->createPartialMock(CartAreaResolver::class, ['resolve']);
        $this->addressModifierMock = $this->createPartialMock(Modifier::class, ['modify']);
        $this->model = $objectManager->getObject(
            RuleService::class,
            [
                'ruleManager' => $this->ruleManagerMock,
                'addressModifier' => $this->addressModifierMock,
                'cartAreaResolver' => $this->cartAreaResolverMock
            ]
        );
    }

    /**
     * Test getAutoAddMetadataRules method
     *
     * @param RuleMetadataInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $expected
     * @param int $cartId
     * @param int|null $storeId
     * @param int $lastQuoteItemId
     * @param CartItemInterface[]|AbstractItem[]|\PHPUnit_Framework_MockObject_MockObject[]|null $lastQuoteItem
     * @param bool $isVirtual
     * @dataProvider getAutoAddMetadataRulesDataProvider
     */
    public function testGetAutoAddMetadataRules(
        $expected,
        $cartId,
        $storeId,
        $lastQuoteItemId,
        $lastQuoteItem,
        $isVirtual
    ) {
        $this->initCartParams($cartId, $storeId, $isVirtual);

        if ($lastQuoteItemId && $lastQuoteItem) {
            $lastQuoteItem = $this->items[0];
            $lastQuoteItem->expects($this->once())
                ->method('getItemId')
                ->willReturn($lastQuoteItemId);
        }
        if (false !== $lastQuoteItem) {
            $this->ruleManagerMock->expects($this->once())
                ->method('getAutoAddMetadataRules')
                ->with(
                    $this->storeId,
                    $this->customerGroupId,
                    $this->address,
                    $this->items,
                    $lastQuoteItem
                )->willReturn($expected);
        }

        $this->assertEquals($expected, $this->model->getAutoAddMetadataRules($cartId, $lastQuoteItemId, $storeId));
    }

    /**
     * Data provider for getAutoAddMetadataRules test
     *
     * @return array
     */
    public function getAutoAddMetadataRulesDataProvider()
    {
        $mtdRules = [$this->getMockForAbstractClass(RuleMetadataInterface::class)];
        return [
            [$mtdRules, 1, 1, null, null, true],
            [$mtdRules, 1, null, 1, 1, false],
            [[], 1, null, 1, false, false]
        ];
    }

    /**
     * Test getPopUpMetadataRules method
     *
     * @param RuleMetadataInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $expected
     * @param int $cartId
     * @param int|null $storeId
     * @param bool $isVirtual
     * @dataProvider baseDataProvider
     */
    public function testGetPopUpMetadataRules(
        $expected,
        $cartId,
        $storeId,
        $isVirtual
    ) {
        $this->initCartParams($cartId, $storeId, $isVirtual);
        $this->ruleManagerMock->expects($this->once())
            ->method('getPopUpMetadataRules')
            ->with(
                $this->storeId,
                $this->customerGroupId,
                $this->address,
                $this->items
            )->willReturn($expected);

        $this->assertEquals($expected, $this->model->getPopUpMetadataRules($cartId, $storeId));
    }

    /**
     * Test getDiscountMetadataRules method
     *
     * @param RuleMetadataInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $expected
     * @param int $cartId
     * @param int|null $storeId
     * @param bool $isVirtual
     * @dataProvider baseDataProvider
     */
    public function testGetDiscountMetadataRules(
        $expected,
        $cartId,
        $storeId,
        $isVirtual
    ) {
        $this->initCartParams($cartId, $storeId, $isVirtual);
        $this->ruleManagerMock->expects($this->once())
            ->method('getDiscountMetadataRules')
            ->with(
                $this->storeId,
                $this->customerGroupId,
                $this->address,
                $this->items
            )->willReturn($expected);

        $this->assertEquals($expected, $this->model->getDiscountMetadataRules($cartId, $storeId));
    }

    /**
     * Data provider for getPopUpMetadataRules and getDiscountMetadataRules test
     *
     * @return array
     */
    public function baseDataProvider()
    {
        $mtdRules = [$this->getMockForAbstractClass(RuleMetadataInterface::class)];
        return [
            [$mtdRules, 1, 1, true],
            [$mtdRules, 1, null, false]
        ];
    }

    /**
     * Test isValidCoupon method
     *
     * @param RuleMetadataInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $expected
     * @param int $cartId
     * @param int|null $storeId
     * @param bool $isVirtual
     * @dataProvider isValidCouponDataProvider
     */
    public function testIsValidCoupon(
        $expected,
        $cartId,
        $storeId,
        $isVirtual
    ) {
        $couponCode = 'code';

        $this->initCartParams($cartId, $storeId, $isVirtual);
        $this->address->expects($this->once())
            ->method('setCouponCode')
            ->with($couponCode)
            ->willReturnSelf();
        $this->ruleManagerMock->expects($this->once())
            ->method('isValidCoupon')
            ->with(
                $couponCode,
                $this->storeId,
                $this->customerGroupId,
                $this->address,
                $this->items
            )->willReturn($expected);

        $this->assertEquals($expected, $this->model->isValidCoupon($couponCode, $cartId, $storeId));
    }

    /**
     * Data provider for isValidCoupon test
     *
     * @return array
     */
    public function isValidCouponDataProvider()
    {
        $mtdRules = [$this->getMockForAbstractClass(RuleMetadataInterface::class)];
        return [
            [$mtdRules, 1, 1, true],
            [$mtdRules, 1, null, false]
        ];
    }

    /**
     * Init cart params
     *
     * @param int $cartId
     * @param int|null $storeId
     * @param bool $isVirtual
     * @return void
     */
    private function initCartParams($cartId, $storeId, $isVirtual)
    {
        $quoteMock = $this->createPartialMock(
            Quote::class,
            [
                'getCustomerGroupId',
                'getStore',
                'setStoreId',
                'isVirtual',
                'getBillingAddress',
                'getShippingAddress',
                'getAllItems'
            ]
        );
        $this->cartAreaResolverMock->expects($this->once())
            ->method('resolve')
            ->with($cartId)
            ->willReturn($quoteMock);

        if ($storeId) {
            $quoteMock->expects($this->once())
                ->method('setStoreId')
                ->with($storeId)
                ->willReturnSelf();
        } else {
            $storeId = 1;
            $storeMock = $this->createPartialMock(Store::class, ['getStoreId']);
            $quoteMock->expects($this->once())
                ->method('getStore')
                ->willReturn($storeMock);
            $storeMock->expects($this->once())
                ->method('getStoreId')
                ->willReturn($storeId);
        }
        $this->storeId = $storeId;

        $quoteMock->expects($this->once())
            ->method('getCustomerGroupId')
            ->willReturn($this->customerGroupId);
        $quoteMock->expects($this->once())
            ->method('isVirtual')
            ->willReturn($isVirtual);

        $this->address = $this->createPartialMock(Quote\Address::class, ['setCouponCode']);
        if ($isVirtual) {
            $quoteMock->expects($this->once())
                ->method('getBillingAddress')
                ->willReturn($this->address);
        } else {
            $quoteMock->expects($this->once())
                ->method('getShippingAddress')
                ->willReturn($this->address);
        }

        $this->addressModifierMock->expects($this->once())
            ->method('modify')
            ->with($this->address, $quoteMock)
            ->willReturn($this->address);

        $this->items = [$this->getMockForAbstractClass(CartItemInterface::class)];
        $quoteMock->expects($this->once())
            ->method('getAllItems')
            ->willReturn($this->items);
    }
}
