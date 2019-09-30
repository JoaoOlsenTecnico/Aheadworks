<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Discount\Calculator\Item;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Rule\RuleMetadataManager;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Item\Validator;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidatorTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Discount\Calculator\Item
 */
class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    private $model;

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
        $this->ruleMetadataManagerMock = $this->createPartialMock(
            RuleMetadataManager::class,
            ['isPromoProduct']
        );
        $this->model = $objectManager->getObject(
            Validator::class,
            [
                'ruleMetadataManager' => $this->ruleMetadataManagerMock
            ]
        );
    }

    /**
     * Test for canApplyDiscount method
     *
     * @dataProvider canApplyDiscountDataProvider
     * @param bool $hasParentItem
     * @param bool $isPromoProduct
     * @param bool $result
     */
    public function testCanApplyDiscount($hasParentItem, $isPromoProduct, $result)
    {
        $itemMock = $this->getMockForAbstractClass(
            AbstractItem::class,
            [],
            '',
            false,
            false,
            true,
            ['getParentItem', 'getAwAfptcId']
        );

        $itemMock->expects($this->once())
            ->method('getParentItem')
            ->willReturn($hasParentItem);

        $ruleMetadataMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        if (!$hasParentItem) {
            $id = 3;
            $itemMock->expects($this->once())
                ->method('getAwAfptcId')
                ->willReturn($id);
            $this->ruleMetadataManagerMock->expects($this->once())
                ->method('isPromoProduct')
                ->with($ruleMetadataMock, $id)
                ->willReturn($isPromoProduct);
        }
        $this->assertSame($result, $this->model->canApplyDiscount($itemMock, $ruleMetadataMock));
    }

    /**
     * Data provider for testCanApplyDiscount method
     */
    public function canApplyDiscountDataProvider()
    {
        return [
            [false, true, true],
            [true, false, false],
            [false, false, false],
            [true, true, false],
        ];
    }
}
