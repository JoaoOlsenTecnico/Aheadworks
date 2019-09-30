<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Service;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Aheadworks\Afptc\Model\Service\GuestRuleService;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class GuestRuleServiceTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Service
 */
class GuestRuleServiceTest extends TestCase
{
    /**
     * @var GuestRuleService
     */
    private $model;

    /**
     * @var RuleManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleManagementMock;

    /**
     * @var QuoteIdMaskFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $quoteIdMaskFactoryMock;

    /**
     * @var RuleMetadataInterface[]|\PHPUnit_Framework_MockObject_MockObject[]
     */
    private $ruleMetadataMock;

    /**
     * @var string
     */
    private $cartIdMask = 'mask';

    /**
     * @var int
     */
    private $cartId = 1;

    /**
     * @var int
     */
    private $storeId = 1;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->ruleManagementMock = $this->getMockForAbstractClass(RuleManagementInterface::class);
        $this->quoteIdMaskFactoryMock = $this->createPartialMock(QuoteIdMaskFactory::class, ['create']);
        $this->model = $objectManager->getObject(
            GuestRuleService::class,
            [
                'ruleManagement' => $this->ruleManagementMock,
                'quoteIdMaskFactory' => $this->quoteIdMaskFactoryMock
            ]
        );

        $quoteIdMaskMock = $this->createPartialMock(QuoteIdMask::class, ['load', 'getQuoteId']);
        $this->quoteIdMaskFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($quoteIdMaskMock);

        $quoteIdMaskMock->expects($this->once())
            ->method('load')
            ->with($this->cartIdMask, 'masked_id')
            ->willReturn($quoteIdMaskMock);
        $quoteIdMaskMock->expects($this->once())
            ->method('getQuoteId')
            ->willReturn($this->cartId);

        $this->ruleMetadataMock = [$this->getMockForAbstractClass(RuleMetadataInterface::class)];
    }

    /**
     * Test getAutoAddMetadataRules method
     */
    public function testGetAutoAddMetadataRules()
    {
        $this->ruleManagementMock->expects($this->once())
            ->method('getAutoAddMetadataRules')
            ->with($this->cartId, $this->storeId)
            ->willReturn($this->ruleMetadataMock);

        $this->assertEquals(
            $this->ruleMetadataMock,
            $this->model->getAutoAddMetadataRules($this->cartIdMask, $this->storeId)
        );
    }

    /**
     * Test getPopUpMetadataRules method
     */
    public function testGetPopUpMetadataRules()
    {
        $this->ruleManagementMock->expects($this->once())
            ->method('getPopUpMetadataRules')
            ->with($this->cartId, $this->storeId)
            ->willReturn($this->ruleMetadataMock);

        $this->assertEquals(
            $this->ruleMetadataMock,
            $this->model->getPopUpMetadataRules($this->cartIdMask, $this->storeId)
        );
    }

    /**
     * Test getDiscountMetadataRules method
     */
    public function testGetDiscountMetadataRules()
    {
        $this->ruleManagementMock->expects($this->once())
            ->method('getDiscountMetadataRules')
            ->with($this->cartId, $this->storeId)
            ->willReturn($this->ruleMetadataMock);

        $this->assertEquals(
            $this->ruleMetadataMock,
            $this->model->getDiscountMetadataRules($this->cartIdMask, $this->storeId)
        );
    }

    /**
     * Test isValidCoupon method
     * @param bool|null $expected
     * @dataProvider isValidCouponDataProvider
     */
    public function testIsValidCoupon($expected)
    {
        $couponCode = 'code';

        $this->ruleManagementMock->expects($this->once())
            ->method('isValidCoupon')
            ->with($couponCode, $this->cartId, $this->storeId)
            ->willReturn($expected);

        $this->assertEquals(
            $expected,
            $this->model->isValidCoupon($couponCode, $this->cartIdMask, $this->storeId)
        );
    }

    /**
     * Data provider for isValidCoupon test
     *
     * @return array
     */
    public function isValidCouponDataProvider()
    {
        return [
            [true],
            [null]
        ];
    }
}
