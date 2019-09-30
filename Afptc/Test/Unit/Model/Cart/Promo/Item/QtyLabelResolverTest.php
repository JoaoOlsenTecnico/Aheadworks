<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Cart\Promo\Item;

use Aheadworks\Afptc\Model\Cart\Promo\Item\QtyLabelResolver;
use Magento\Quote\Api\Data\CartItemExtensionInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class QtyLabelResolverTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Cart\Promo\Item
 */
class QtyLabelResolverTest extends TestCase
{
    /**
     * @var QtyLabelResolver
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
        $this->model = $objectManager->getObject(
            QtyLabelResolver::class,
            []
        );
    }

    /**
     * Test isValid method
     *
     * @param bool $expected
     * @param array $itemData
     * @dataProvider resolveDataProvider
     */
    public function testResolve($expected, $itemData)
    {
        $cartItemMock = $this->getMockForAbstractClass(
            CartItemInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getAwAfptcQty', 'getAwAfptcIsPromo', 'getAwAfptcPercent']
        );
        $cartItemExtension = $this->getMockForAbstractClass(
            CartItemExtensionInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getAwAfptcRules']
        );

        $cartItemMock->expects($this->once())
            ->method('getAwAfptcQty')
            ->willReturn($itemData['awAfptcQty']);
        if ($itemData['awAfptcIsPromo'] && $itemData['awAfptcQty'] > 0) {
            $cartItemMock->expects($this->once())
                ->method('getAwAfptcIsPromo')
                ->willReturn($itemData['awAfptcIsPromo']);
            $cartItemMock->expects($this->once())
                ->method('getQty')
                ->willReturn($itemData['qty']);
            $cartItemMock->expects($this->once())
                ->method('getAwAfptcPercent')
                ->willReturn($itemData['awAfptcPercent']);
            $cartItemMock->expects($this->once())
                ->method('getExtensionAttributes')
                ->willReturn($cartItemExtension);
            $cartItemExtension->expects($this->once())
                ->method('getAwAfptcRules')
                ->willReturn($itemData['awAfptcRules']);
        }

        $this->assertEquals($expected, $this->model->resolve($cartItemMock));
    }

    /**
     * Data provider for resolve test
     *
     * @return array
     */
    public function resolveDataProvider()
    {
        return [
            [
                __('%discount% off', ['qty' => 1, 'discount' => 10.0]),
                [
                    'awAfptcQty' => 1,
                    'awAfptcIsPromo' => true,
                    'qty' => 1,
                    'awAfptcPercent' => 10.0000,
                    'awAfptcRules' => [['rule_id' => 1, 'qty' => 1]],
                ]
            ],
            [
                __('Free', ['qty' => 1, 'discount' => 100.0]),
                [
                    'awAfptcQty' => 1,
                    'awAfptcIsPromo' => true,
                    'qty' => 1,
                    'awAfptcPercent' => 100.0000,
                    'awAfptcRules' => [['rule_id' => 1, 'qty' => 1]],
                ]
            ],
            [
                __('1 is free', ['qty' => 1, 'discount' => 100.0]),
                [
                    'awAfptcQty' => 1,
                    'awAfptcIsPromo' => true,
                    'qty' => 2,
                    'awAfptcPercent' => 100.0000,
                    'awAfptcRules' => [['rule_id' => 1, 'qty' => 2]],
                ]
            ],
            [
                __('1 is %discount% off', ['qty' => 1, 'discount' => 10.0]),
                [
                    'awAfptcQty' => 1,
                    'awAfptcIsPromo' => true,
                    'qty' => 2,
                    'awAfptcPercent' => 10.0000,
                    'awAfptcRules' => [['rule_id' => 1, 'qty' => 1]],
                ]
            ],
            [
                __('%qty are free', ['qty' => 2, 'discount' => 100.0]),
                [
                    'awAfptcQty' => 2,
                    'awAfptcIsPromo' => true,
                    'qty' => 2,
                    'awAfptcPercent' => 100.0000,
                    'awAfptcRules' => [['rule_id' => 1, 'qty' => 2]],
                ]
            ],
            [
                __('%qty are %discount% off', ['qty' => 2, 'discount' => 10.0]),
                [
                    'awAfptcQty' => 2,
                    'awAfptcIsPromo' => true,
                    'qty' => 2,
                    'awAfptcPercent' => 10.0000,
                    'awAfptcRules' => [['rule_id' => 1, 'qty' => 2]],
                ]
            ],
            [
                __('Items have different discounts', ['qty' => 2, 'discount' => 30.0]),
                [
                    'awAfptcQty' => 2,
                    'awAfptcIsPromo' => true,
                    'qty' => 2,
                    'awAfptcPercent' => 30.0000,
                    'awAfptcRules' => [['rule_id' => 1, 'qty' => 1], ['rule_id' => 2, 'qty' => 1]],
                ]
            ],
            [
                '',
                [
                    'awAfptcQty' => 0,
                    'awAfptcIsPromo' => true
                ]
            ],
            [
                '',
                [
                    'awAfptcQty' => 1,
                    'awAfptcIsPromo' => false
                ]
            ]
        ];
    }
}
