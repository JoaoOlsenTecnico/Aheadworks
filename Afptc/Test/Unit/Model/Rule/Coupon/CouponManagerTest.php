<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Coupon;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\SalesRule\Api\Data\RuleInterface as SalesRuleInterface;
use Magento\SalesRule\Api\Data\CouponInterface;
use Magento\SalesRule\Api\Data\CouponInterfaceFactory;
use Magento\SalesRule\Api\CouponRepositoryInterface;
use Aheadworks\Afptc\Model\Rule\Coupon\CouponManager;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class CouponManagerTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Coupon
 */
class CouponManagerTest extends TestCase
{
    /**
     * @var CouponManager
     */
    private $model;

    /**
     * @var CouponInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $couponFactoryMock;

    /**
     * @var CouponRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $couponRepositoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->couponFactoryMock = $this->createPartialMock(CouponInterfaceFactory::class, ['create']);
        $this->couponRepositoryMock = $this->getMockForAbstractClass(CouponRepositoryInterface::class);
        $this->model = $objectManager->getObject(
            CouponManager::class,
            [
                'couponFactory' => $this->couponFactoryMock,
                'couponRepository' => $this->couponRepositoryMock
            ]
        );
    }

    /**
     * Test for saveCoupon method
     *
     * @dataProvider saveCouponDataProvider
     * @param int|null $couponId
     */
    public function testSaveCoupon($couponId)
    {
        $couponMock = $this->getMockForAbstractClass(CouponInterface::class);
        $this->couponFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($couponMock);
        $afptcRuleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $salesRuleMock = $this->getMockForAbstractClass(SalesRuleInterface::class);
        if ($couponId) {
            $afptcRuleMock->expects($this->once())
                ->method('getCouponId')
                ->willReturn($couponId);
            $couponMock->expects($this->once())
                ->method('setCouponId')
                ->with($couponId)
                ->willReturnSelf();
        }

        $salesRuleId = 4;
        $couponCode = 'test';
        $salesRuleMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn($salesRuleId);
        $afptcRuleMock->expects($this->once())
            ->method('getCouponCode')
            ->willReturn($couponCode);
        $couponMock->expects($this->once())
            ->method('setRuleId')
            ->with($salesRuleId)
            ->willReturnSelf();
        $couponMock->expects($this->once())
            ->method('setIsPrimary')
            ->with(true)
            ->willReturnSelf();
        $couponMock->expects($this->once())
            ->method('setCode')
            ->with($couponCode)
            ->willReturnSelf();
        $couponMock->expects($this->once())
            ->method('setType')
            ->with(CouponInterface::TYPE_MANUAL)
            ->willReturnSelf();
        $this->couponRepositoryMock->expects($this->once())
            ->method('save')
            ->with($couponMock)
            ->willReturn($couponMock);

        $this->assertEquals($couponMock, $this->model->saveCoupon($afptcRuleMock, $salesRuleMock));
    }

    /**
     * Data provider for testSaveCoupon method
     */
    public function saveCouponDataProvider()
    {
        return [
            [5],
            [null],
        ];
    }

    /**
     * Test save coupon method on exception
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testSaveCouponOnException()
    {
        $couponMock = $this->getMockForAbstractClass(CouponInterface::class);
        $this->couponFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($couponMock);
        $afptcRuleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $salesRuleMock = $this->getMockForAbstractClass(SalesRuleInterface::class);
        $exception = new NoSuchEntityException(__('Exception message.'));
        $couponMock->expects($this->once())
            ->method('setRuleId')
            ->willReturnSelf();
        $couponMock->expects($this->once())
            ->method('setIsPrimary')
            ->willReturnSelf();
        $couponMock->expects($this->once())
            ->method('setCode')
            ->willReturnSelf();
        $couponMock->expects($this->once())
            ->method('setType')
            ->willReturnSelf();
        $this->couponRepositoryMock->expects($this->once())
            ->method('save')
            ->with($couponMock)
            ->willThrowException($exception);
        $this->model->saveCoupon($afptcRuleMock, $salesRuleMock);
    }

    /**
     * Test form getSalesRuleId method
     */
    public function testGetSalesRuleId()
    {
        $couponId = 2;
        $ruleId = 1;
        $couponMock = $this->getMockForAbstractClass(CouponInterface::class);
        $this->couponRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($couponId)
            ->willReturn($couponMock);
        $couponMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn($ruleId);

        $this->assertSame($ruleId, $this->model->getSalesRuleId($couponId));
    }

    /**
     * Test form getSalesRuleId method on exception
     */
    public function testGetSalesRuleIdOnException()
    {
        $couponId = 2;
        $exception = new \Exception(__('Exception message.'));
        $this->couponRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($couponId)
            ->willThrowException($exception);

        $this->assertSame(null, $this->model->getSalesRuleId($couponId));
    }
}
