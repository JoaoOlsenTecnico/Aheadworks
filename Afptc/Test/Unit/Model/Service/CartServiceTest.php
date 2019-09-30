<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Service;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Service\CartService;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Cart\Cart;
use Aheadworks\Afptc\Model\Cart\Converter\ToCartItem;
use Magento\Quote\Api\CartRepositoryInterface;
use Aheadworks\Afptc\Model\Cart\Area\Resolver as CartAreaResolver;

/**
 * Class CartServiceTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Service
 */
class CartServiceTest extends TestCase
{
    /**
     * @var CartService
     */
    private $model;

    /**
     * @var CartRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartRepositoryMock;

    /**
     * @var ToCartItem|\PHPUnit_Framework_MockObject_MockObject
     */
    private $converterMock;

    /**
     * @var Cart|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartMock;

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
        $this->converterMock = $this->createPartialMock(ToCartItem::class, ['convert']);
        $this->cartMock = $this->createPartialMock(
            Cart::class,
            ['addProductsToCart', 'removeUnusedPromoData']
        );
        $this->cartAreaResolverMock = $this->createPartialMock(CartAreaResolver::class, ['resolve']);
        $this->model = $objectManager->getObject(
            CartService::class,
            [
                'cartRepository' => $this->cartRepositoryMock,
                'converter' => $this->converterMock,
                'cart' => $this->cartMock,
                'cartAreaResolver' => $this->cartAreaResolverMock
            ]
        );
    }

    /**
     * Test addPromoProducts method
     *
     * @param CartItemInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $newCartItems
     * @dataProvider addPromoProductsDataProvider
     */
    public function testAddPromoProducts($newCartItems)
    {
        $cartId = 1;
        $ruleMetadataMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $metadataRules = [$ruleMetadataMock];

        $this->converterMock->expects($this->once())
            ->method('convert')
            ->with($cartId, $metadataRules)
            ->willReturn($newCartItems);

        if ($newCartItems) {
            $quoteMock = $this->getMockForAbstractClass(CartInterface::class);
            $this->cartAreaResolverMock->expects($this->once())
                ->method('resolve')
                ->with($cartId)
                ->willReturn($quoteMock);

            $this->cartMock->expects($this->once())
                ->method('addProductsToCart')
                ->with($quoteMock, $newCartItems)
                ->willReturn($quoteMock);
        }

        $this->model->addPromoProducts($cartId, $metadataRules);
    }

    /**
     * Data provider for addPromoProducts test
     *
     * @return array
     */
    public function addPromoProductsDataProvider()
    {
        $cartItemMock = $this->getMockForAbstractClass(CartItemInterface::class);
        return [
            [[$cartItemMock]],
            [[]]
        ];
    }

    /**
     * Test addPromoProducts method on exception
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testAddPromoProductsOnException()
    {
        $cartId = 1;
        $ruleMetadataMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $metadataRules = [$ruleMetadataMock];
        $cartItemMock = $this->getMockForAbstractClass(CartItemInterface::class);
        $newCartItems = [$cartItemMock];
        $exception = new LocalizedException(__('exception'));

        $this->converterMock->expects($this->once())
            ->method('convert')
            ->with($cartId, $metadataRules)
            ->willReturn($newCartItems);

        $this->cartAreaResolverMock->expects($this->once())
            ->method('resolve')
            ->with($cartId)
            ->willThrowException($exception);

        $this->model->addPromoProducts($cartId, $metadataRules);
    }

    /**
     * Test removeUnusedPromoData method
     * @param CartInterface|\PHPUnit_Framework_MockObject_MockObject|null $updatedQuote
     * @dataProvider removeUnusedPromoDataDataProvider
     */
    public function testRemoveUnusedPromoData($updatedQuote)
    {
        $cartId = 1;
        $quoteMock = $this->getMockForAbstractClass(CartInterface::class);
        $this->cartRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($quoteMock);

        $this->cartMock->expects($this->once())
            ->method('removeUnusedPromoData')
            ->with($quoteMock)
            ->willReturn($updatedQuote);

        if ($updatedQuote) {
            $this->cartRepositoryMock->expects($this->once())
                ->method('save')
                ->with($updatedQuote);
        }
        $this->model->removeUnusedPromoData($cartId);
    }

    /**
     * Data provider for removeUnusedPromoData test
     *
     * @return array
     */
    public function removeUnusedPromoDataDataProvider()
    {
        $quote = $this->getMockForAbstractClass(CartInterface::class);
        return [
            [$quote],
            [null]
        ];
    }

    /**
     * Test removeUnusedPromoData method on exception
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testRemoveUnusedPromoDataOnException()
    {
        $cartId = 1;
        $exception = new LocalizedException(__('exception'));

        $this->cartRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willThrowException($exception);

        $this->model->removeUnusedPromoData($cartId);
    }
}
