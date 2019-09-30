<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Validator;

use Aheadworks\Afptc\Model\Rule\Validator\Coupon;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Source\Rule\Scenario;

/**
 * Class CouponTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Validator
 */
class CouponTest extends TestCase
{
    /**
     * @var Coupon
     */
    private $model;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(Coupon::class);
    }

    /**
     * Test for isValid method
     *
     * @dataProvider isValidDataProvider
     * @param RuleInterface $ruleMock
     * @param bool $result
     */
    public function testIsValid($ruleMock, $result)
    {
        $this->assertSame($result, $this->model->isValid($ruleMock));
    }

    /**
     * Data provider for testIsValid method
     */
    public function isValidDataProvider()
    {
        $validRuleMock1 = $this->getMockForAbstractClass(RuleInterface::class);
        $validRuleMock1->expects($this->once())
            ->method('getScenario')
            ->willReturn(Scenario::BUY_X_GET_Y);

        $validRuleMock2 = $this->getMockForAbstractClass(RuleInterface::class);
        $validRuleMock2->expects($this->once())
            ->method('getScenario')
            ->willReturn(Scenario::COUPON);
        $validRuleMock2->expects($this->once())
            ->method('getCouponCode')
            ->willReturn('coupon1');

        $invalidRuleMock1 = $this->getMockForAbstractClass(RuleInterface::class);
        $invalidRuleMock1->expects($this->once())
            ->method('getScenario')
            ->willReturn(Scenario::COUPON);
        $invalidRuleMock1->expects($this->once())
            ->method('getCouponCode')
            ->willReturn(null);

        return [
            'valid1' => [$validRuleMock1, true],
            'valid2' => [$validRuleMock2, true],
            'invalid1' => [$invalidRuleMock1, false],
        ];
    }
}
