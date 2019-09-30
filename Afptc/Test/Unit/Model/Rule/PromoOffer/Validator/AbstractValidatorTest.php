<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer\Validator;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Validator\AbstractValidator;
use Aheadworks\Afptc\Model\Rule\Condition\Loader as ConditionLoader;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\Complete as CartRule;

/**
 * Class AbstractValidatorTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer\Validator
 */
class AbstractValidatorTest extends TestCase
{
    /**
     * @var AbstractValidator
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
        $this->model = $objectManager->getObject(
            AbstractValidator::class,
            [
                'conditionLoader' => $this->conditionLoaderMock
            ]
        );
    }

    /**
     * Test for isValidRule method
     *
     * @dataProvider isValidRuleDataProvider
     * @param int $validate
     * @param int $validateByEntityId
     * @param AbstractItem|null $quoteItemMock
     * @param string $result
     */
    public function testIsValidRule($validate, $validateByEntityId, $result, $quoteItemMock = null)
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $addressMock = $this->createPartialMock(Address::class, ['getCouponCode']);
        $cartRuleMock = $this->createPartialMock(
            CartRule::class,
            ['validateByEntityId', 'validate']
        );
        $this->conditionLoaderMock->expects($this->any())
            ->method('loadCartCondition')
            ->willReturn($cartRuleMock);
        $cartRuleMock->expects($this->any())
            ->method('validate')
            ->with($addressMock)
            ->willReturn($validate);

        $cartRuleMock->expects($this->any())
            ->method('validateByEntityId')
            ->with(20)
            ->willReturn($validateByEntityId);

        $this->assertSame($result, $this->model->isValidRule($ruleMock, $addressMock, $quoteItemMock));
    }

    /**
     * Data provider for testIsValidRule method
     */
    public function isValidRuleDataProvider()
    {
        $quoteItemMock = $this->createPartialMock(
            AbstractItem::class,
            ['getQuote', 'getAddress', 'getOptionByCode', 'getProductId']
        );
        $quoteItemMock->expects($this->any())
            ->method('getProductId')
            ->willReturn(20);
        return [
            'valid1' => [false, true, true, $quoteItemMock],
            'valid2' => [true, false, true, null],
            'not valid' => [false, false, false, null],
        ];
    }

    /**
     * Test for testIsValidItems method
     *
     * @dataProvider isValidItemsDataProvider
     * @param int $isPromo
     * @param int $isParentItem
     * @param string $result
     */
    public function testIsValidItems($isPromo, $isParentItem, $result)
    {
        $item = $this->createPartialMock(
            AbstractItem::class,
            ['getQuote', 'getAddress', 'getOptionByCode', 'getProductId', 'getAwAfptcIsPromo', 'getParentItem']
        );
        $item->expects($this->any())
            ->method('getAwAfptcIsPromo')
            ->willReturn($isPromo);
        $item->expects($this->any())
            ->method('getParentItem')
            ->willReturn($isParentItem);

        $this->assertSame($result, $this->model->isValidItems([$item]));
    }

    /**
     * Data provider for testIsValidItems method
     */
    public function isValidItemsDataProvider()
    {
        return [
            'invalid1' => [true, true, false],
            'invalid2' => [false, true, false],
            'invalid3' => [true, false, false],
            'valid1' => [false, false, true],
        ];
    }
}
