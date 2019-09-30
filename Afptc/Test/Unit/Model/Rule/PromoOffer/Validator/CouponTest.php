<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer\Validator;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Validator\Coupon;
use Aheadworks\Afptc\Model\Rule\Condition\Loader as ConditionLoader;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\Complete as CartRule;

/**
 * Class CouponTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer\Validator
 */
class CouponTest extends TestCase
{
    /**
     * @var Coupon
     */
    private $model;

    /**
     * @var ConditionLoader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $conditionLoaderMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->conditionLoaderMock = $this->createPartialMock(
            ConditionLoader::class,
            ['loadCartCondition']
        );
        $cartRuleMock = $this->createPartialMock(
            CartRule::class,
            ['validateByEntityId', 'validate']
        );
        $this->conditionLoaderMock->expects($this->any())
            ->method('loadCartCondition')
            ->willReturn($cartRuleMock);
        $cartRuleMock->expects($this->any())
            ->method('validate')
            ->willReturn(true);
        $this->model = $objectManager->getObject(
            Coupon::class,
            [
                'conditionLoader' => $this->conditionLoaderMock
            ]
        );
    }

    /**
     * Test for isValidRule method
     *
     * @dataProvider isValidRuleDataProvider
     * @param string $ruleMockCoupon
     * @param string $quoteCoupon
     * @param string $result
     */
    public function testIsValidRule($ruleMockCoupon, $quoteCoupon, $result)
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $ruleMock->expects($this->once())
            ->method('getCouponCode')
            ->willReturn($ruleMockCoupon);
        $addressMock = $this->createPartialMock(Address::class, ['getCouponCode']);
        $addressMock->expects($this->once())
            ->method('getCouponCode')
            ->willReturn($quoteCoupon);
        $quoteItemMock = $this->createPartialMock(AbstractItem::class, ['getQuote', 'getAddress', 'getOptionByCode']);
        $this->assertSame($result, $this->model->isValidRule($ruleMock, $addressMock, $quoteItemMock));
    }

    /**
     * Data provider for testIsValidRule method
     */
    public function isValidRuleDataProvider()
    {
        return [
            'valid' => ['test','test', true],
            'not valid1' => ['test1',null, false],
            'not valid2' => [null,'test2', false]
        ];
    }
}
