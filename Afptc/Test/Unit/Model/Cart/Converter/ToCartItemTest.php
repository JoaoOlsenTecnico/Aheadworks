<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Cart\Converter;

use Aheadworks\Afptc\Api\Data\CartItemRuleInterface;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Model\Cart\Converter\ToCartItem;
use Magento\Quote\Api\Data\CartItemExtensionInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Cart\Converter\ToCartItem\Validator;
use Magento\Quote\Api\Data\CartItemInterfaceFactory;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty as QtyResolver;
use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory;
use Aheadworks\Afptc\Api\Data\CartItemRuleInterfaceFactory;

/**
 * Class ToCartItemTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Cart\Converter
 */
class ToCartItemTest extends TestCase
{
    /**
     * @var ToCartItem
     */
    private $model;

    /**
     * @var CartItemExtensionInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartItemExtensionFactoryMock;

    /**
     * @var CartItemRuleInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartItemRuleFactoryMock;

    /**
     * @var CartItemInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartItemFactoryMock;

    /**
     * @var QtyResolver|\PHPUnit_Framework_MockObject_MockObject
     */
    private $qtyResolverMock;

    /**
     * @var Validator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $validatorMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->cartItemFactoryMock = $this->createPartialMock(CartItemInterfaceFactory::class, ['create']);
        $this->cartItemExtensionFactoryMock = $this->createPartialMock(
            CartItemExtensionInterfaceFactory::class,
            ['create']
        );
        $this->cartItemRuleFactoryMock = $this->createPartialMock(CartItemRuleInterfaceFactory::class, ['create']);
        $this->qtyResolverMock = $this->createPartialMock(QtyResolver::class, ['resolveQtyToDiscountByStock']);
        $this->validatorMock = $this->createPartialMock(Validator::class, ['isValid']);
        $this->model = $objectManager->getObject(
            ToCartItem::class,
            [
                'cartItemFactory' => $this->cartItemFactoryMock,
                'cartItemExtensionFactory' => $this->cartItemExtensionFactoryMock,
                'cartItemRuleFactory' => $this->cartItemRuleFactoryMock,
                'qtyResolver' => $this->qtyResolverMock,
                'validator' => $this->validatorMock
            ]
        );
    }

    /**
     * Test convert method
     *
     * @param bool $isValid
     * @param RuleMetadataInterface[] $metadataRulesMock
     * @param RuleMetadataPromoProductInterface[] $promoProductsMock
     * @param array $promoProductData
     * @dataProvider convertDataProvider
     */
    public function testConvert($isValid, $metadataRulesMock, $promoProductsMock, $promoProductData)
    {
        $cartId = 1;
        $ruleId = 1;
        $expected = [];
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);

        $promoProductsMock[0]->expects($this->once())
            ->method('getProductSku')
            ->willReturn($promoProductData['sku']);
        $promoProductsMock[0]->expects($this->once())
            ->method('getOption')
            ->willReturn($promoProductData['option']);

        $this->validatorMock->expects($this->once())
            ->method('isValid')
            ->with($metadataRulesMock[0])
            ->willReturn($isValid);

        if ($isValid) {
            $metadataRulesMock[0]->expects($this->once())
                ->method('getPromoProducts')
                ->willReturn($promoProductsMock);
            $metadataRulesMock[0]->expects($this->exactly(2))
                ->method('getRule')
                ->willReturn($ruleMock);
            $this->qtyResolverMock->expects($this->once())
                ->method('resolveQtyToDiscountByStock')
                ->with($promoProductsMock[0], $ruleMock)
                ->willReturn($promoProductData['qty']);

            if ($promoProductData['qty']) {
                $cartItemMock = $this->getMockForAbstractClass(CartItemInterface::class);
                $this->cartItemFactoryMock->expects($this->once())
                    ->method('create')
                    ->willReturn($cartItemMock);
                $cartItemMock->expects($this->once())
                    ->method('setSku')
                    ->with($promoProductData['sku'])
                    ->willReturnSelf();
                $cartItemMock->expects($this->once())
                    ->method('setQuoteId')
                    ->with($cartId)
                    ->willReturnSelf();
                $cartItemMock->expects($this->once())
                    ->method('setQty')
                    ->with($promoProductData['qty'])
                    ->willReturnSelf();

                $ruleMock->expects($this->once())
                    ->method('getRuleId')
                    ->willReturn($ruleId);
                $cartItemRuleMock = $this->getMockForAbstractClass(CartItemRuleInterface::class);
                $cartItemRuleMock->expects($this->once())
                    ->method('setQty')
                    ->with($promoProductData['qty'])
                    ->willReturnSelf();
                $cartItemRuleMock->expects($this->once())
                    ->method('setRuleId')
                    ->with($ruleId)
                    ->willReturnSelf();
                $this->cartItemRuleFactoryMock->expects($this->once())
                    ->method('create')
                    ->willReturn($cartItemRuleMock);

                $cartItemExtensionMock = $this->getMockForAbstractClass(
                    CartItemExtensionInterface::class,
                    [],
                    '',
                    true,
                    true,
                    true,
                    ['setAwUniqueId', 'setAwAfptcIsPromo', 'setAwAfptcRulesRequest']
                );
                $cartItemExtensionMock->expects($this->once())
                    ->method('setAwUniqueId')
                    ->willReturnSelf();
                $cartItemExtensionMock->expects($this->once())
                    ->method('setAwAfptcIsPromo')
                    ->with(true)
                    ->willReturnSelf();
                $cartItemExtensionMock->expects($this->once())
                    ->method('setAwAfptcRulesRequest')
                    ->with([$cartItemRuleMock])
                    ->willReturnSelf();
                $this->cartItemExtensionFactoryMock->expects($this->once())
                    ->method('create')
                    ->willReturn($cartItemExtensionMock);

                $cartItemMock->expects($this->once())
                    ->method('setExtensionAttributes')
                    ->with($cartItemExtensionMock)
                    ->willReturnSelf();
                $expected = [$cartItemMock];
            }
        }

        $this->assertEquals($expected, $this->model->convert($cartId, $metadataRulesMock));
    }

    /**
     * Data provider for convert test
     *
     * @return array
     */
    public function convertDataProvider()
    {
        $metadataRuleMock1 = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $promoProductMock1 = $this->getMockForAbstractClass(RuleMetadataPromoProductInterface::class);
        $promoProductData1 = [
            'qty' => 1,
            'sku' => 'sku',
            'option' => null
        ];
        $metadataRuleMock2 = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $promoProductMock2 = $this->getMockForAbstractClass(RuleMetadataPromoProductInterface::class);
        $promoProductData2 = [
            'qty' => 0,
            'sku' => 'sku',
            'option' => null
        ];

        return [
            [true, [$metadataRuleMock1], [$promoProductMock1], $promoProductData1],
            [true, [$metadataRuleMock2], [$promoProductMock2], $promoProductData2],
            [false, [$metadataRuleMock1], [$promoProductMock1], $promoProductData1]
        ];
    }
}
