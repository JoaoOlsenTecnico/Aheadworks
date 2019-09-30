<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Coupon;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\SalesRule\Api\Data\RuleInterface as SalesRuleInterface;
use Magento\SalesRule\Api\Data\RuleInterfaceFactory;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Aheadworks\Afptc\Model\Source\Rule\Scenario;
use Aheadworks\Afptc\Model\Rule\Coupon\SalesRuleManager;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class SalesRuleManagerTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Coupon
 */
class SalesRuleManagerTest extends TestCase
{
    /**
     * @var SalesRuleManager
     */
    private $model;

    /**
     * @var RuleInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleFactoryMock;

    /**
     * @var RuleRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleRepositoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->ruleFactoryMock = $this->createPartialMock(RuleInterfaceFactory::class, ['create']);
        $this->ruleRepositoryMock = $this->getMockForAbstractClass(RuleRepositoryInterface::class);
        $this->model = $objectManager->getObject(
            SalesRuleManager::class,
            [
                'ruleFactory' => $this->ruleFactoryMock,
                'ruleRepository' => $this->ruleRepositoryMock
            ]
        );
    }

    /**
     * Test for saveSalesRule method
     *
     * @dataProvider saveSalesRuleDataProvider
     * @param int|null $salesRuleId
     */
    public function testSaveSalesRule($salesRuleId)
    {
        $afptcRuleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $salesRuleMock = $this->getMockForAbstractClass(SalesRuleInterface::class);

        $this->ruleFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($salesRuleMock);

        $afptcRuleMock->expects($this->once())
            ->method('isActive')
            ->willReturn(true);
        $afptcRuleMock->expects($this->once())
            ->method('getScenario')
            ->willReturn(Scenario::COUPON);
        $salesRuleMock->expects($this->once())
            ->method('setIsActive')
            ->with(true)
            ->willReturnSelf();

        if ($salesRuleId) {
            $salesRuleMock->expects($this->once())
                ->method('setRuleId')
                ->with($salesRuleId)
                ->willReturnSelf();
        }

        $customerGroupIds = ['1','2'];
        $afptcRuleMock->expects($this->once())
            ->method('getCustomerGroupIds')
            ->willReturn($customerGroupIds);
        $salesRuleMock->expects($this->once())
            ->method('setCustomerGroupIds')
            ->with($customerGroupIds)
            ->willReturnSelf();

        $fromDate = '12.12.2012';
        $afptcRuleMock->expects($this->once())
            ->method('getFromDate')
            ->willReturn($fromDate);
        $salesRuleMock->expects($this->once())
            ->method('setFromDate')
            ->with($fromDate)
            ->willReturnSelf();
        $toDate = '15.12.2012';
        $afptcRuleMock->expects($this->once())
            ->method('getToDate')
            ->willReturn($toDate);
        $salesRuleMock->expects($this->once())
            ->method('setToDate')
            ->with($toDate)
            ->willReturnSelf();

        $websiteIds = ['1','2'];
        $afptcRuleMock->expects($this->once())
            ->method('getWebsiteIds')
            ->willReturn($websiteIds);
        $salesRuleMock->expects($this->once())
            ->method('setWebsiteIds')
            ->with($websiteIds)
            ->willReturnSelf();

        $salesRuleMock->expects($this->once())
            ->method('setCouponType')
            ->with(SalesRuleInterface::COUPON_TYPE_SPECIFIC_COUPON)
            ->willReturnSelf();

        $name = 'rule1';
        $afptcRuleMock->expects($this->exactly(2))
            ->method('getName')
            ->willReturn($name);
        $salesRuleMock->expects($this->once())
            ->method('setName')
            ->with($name)
            ->willReturnSelf();

        $afptcRuleMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn(1);
        $afptcRuleMock->expects($this->once())
            ->method('getDescription')
            ->willReturn('description');
        $salesRuleMock->expects($this->once())
            ->method('setDescription')
            ->willReturnSelf();

        $salesRuleMock->expects($this->once())
            ->method('setDiscountAmount')
            ->with(0)
            ->willReturnSelf();
        $salesRuleMock->expects($this->once())
            ->method('setDiscountQty')
            ->with(0)
            ->willReturnSelf();
        $salesRuleMock->expects($this->once())
            ->method('setDiscountStep')
            ->with(0)
            ->willReturnSelf();
        $salesRuleMock->expects($this->once())
            ->method('setUsesPerCoupon')
            ->with(0)
            ->willReturnSelf();
        $salesRuleMock->expects($this->once())
            ->method('setUsesPerCustomer')
            ->with(0)
            ->willReturnSelf();
        $salesRuleMock->expects($this->once())
            ->method('setTimesUsed')
            ->with(0)
            ->willReturnSelf();
        $salesRuleMock->expects($this->once())
            ->method('setSortOrder')
            ->with(0)
            ->willReturnSelf();
        $salesRuleMock->expects($this->once())
            ->method('setUseAutoGeneration')
            ->with(false)
            ->willReturnSelf();
        $salesRuleMock->expects($this->once())
            ->method('setStopRulesProcessing')
            ->with(true)
            ->willReturnSelf();
        $salesRuleMock->expects($this->once())
            ->method('setApplyToShipping')
            ->with(true)
            ->willReturnSelf();
        $salesRuleMock->expects($this->once())
            ->method('setSimpleAction')
            ->with(SalesRuleInterface::DISCOUNT_ACTION_BY_PERCENT)
            ->willReturnSelf();

        $this->ruleRepositoryMock->expects($this->once())
            ->method('save')
            ->with($salesRuleMock)
            ->willReturn($salesRuleMock);

        $this->assertEquals($salesRuleMock, $this->model->saveSalesRule($afptcRuleMock, $salesRuleId));
    }

    /**
     * Data provider for testSaveSalesRule method
     */
    public function saveSalesRuleDataProvider()
    {
        return [
            [5],
            [null],
        ];
    }

    /**
     * Test for deleteSalesRule method
     */
    public function testDeleteSalesRule()
    {
        $salesRuleId = 3;
        $this->ruleRepositoryMock->expects($this->once())
            ->method('deleteById')
            ->with($salesRuleId)
            ->willReturn(true);

        $this->assertEquals(true, $this->model->deleteSalesRule($salesRuleId));
    }

    /**
     * Test for deleteSalesRule method on exception
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testDeleteSalesRuleOnException()
    {
        $exception = new NoSuchEntityException(__('Exception message.'));
        $salesRuleId = 3;
        $this->ruleRepositoryMock->expects($this->once())
            ->method('deleteById')
            ->with($salesRuleId)
            ->willThrowException($exception);

        $this->assertEquals(true, $this->model->deleteSalesRule($salesRuleId));
    }
}
