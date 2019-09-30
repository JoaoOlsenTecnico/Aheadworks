<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule;
use Aheadworks\Afptc\Model\Rule\Listing\Builder;
use Aheadworks\Afptc\Model\Source\Rule\HowToOfferType;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor\Pool;
use Aheadworks\Afptc\Model\Source\Rule\Scenario;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Manager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Validator;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class ManagerTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer
 */
class ManagerTest extends TestCase
{
    /**
     * @var Manager
     */
    private $model;

    /**
     * @var Builder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleBuilderMock;

    /**
     * @var Validator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $validatorMock;

    /**
     * @var ToMetadataRule|\PHPUnit_Framework_MockObject_MockObject
     */
    private $converterMock;

    /**
     * @var SearchCriteriaBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->ruleBuilderMock = $this->createPartialMock(
            Builder::class,
            ['getSearchCriteriaBuilder', 'addFilter', 'getActiveRules']
        );
        $this->validatorMock = $this->createPartialMock(
            Validator::class,
            ['getValidRules']
        );
        $this->converterMock = $this->createPartialMock(
            ToMetadataRule::class,
            ['convert']
        );

        $this->searchCriteriaMock = $this->createPartialMock(
            SearchCriteriaBuilder::class,
            ['addFilter', 'create']
        );

        $this->model = $objectManager->getObject(
            Manager::class,
            [
                'ruleBuilder' => $this->ruleBuilderMock,
                'validator' => $this->validatorMock,
                'converter' => $this->converterMock
            ]
        );
    }

    /**
     * Test for getAutoAddMetadataRules method
     *
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testGetAutoAddMetadataRules()
    {
        $storeId = 1;
        $customerGroupId = 2;
        $this->prepareRuleBuilder();
        $this->searchCriteriaMock->expects($this->once())
            ->method('addFilter')
            ->with(RuleInterface::HOW_TO_OFFER, HowToOfferType::AUTO_ADDING)
            ->willReturnSelf();
        $itemMock = $this->createPartialMock(AbstractItem::class, ['getQuote', 'getAddress', 'getOptionByCode']);
        $addressMock = $this->createPartialMock(Address::class, []);
        $lastQuoteItemMock = $this->createPartialMock(
            AbstractItem::class,
            ['getQuote', 'getAddress', 'getOptionByCode']
        );
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);

        $this->ruleBuilderMock->expects($this->once())
            ->method('getActiveRules')
            ->with($storeId, $customerGroupId)
            ->willReturn([$ruleMock]);

        $this->validatorMock->expects($this->once())
            ->method('getValidRules')
            ->with([$ruleMock], $addressMock, [$itemMock], $lastQuoteItemMock)
            ->willReturn([$ruleMock]);

        $ruleMockMetadata = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $this->converterMock->expects($this->once())
            ->method('convert')
            ->with([$ruleMock], [$itemMock], Pool::AUTO_ADD_PROCESSOR)
            ->willReturn([$ruleMockMetadata]);

        $this->model->getAutoAddMetadataRules(
            $storeId,
            $customerGroupId,
            $addressMock,
            [$itemMock],
            $lastQuoteItemMock
        );
    }

    /**
     * Test for getPopUpMetadataRules method
     *
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testGetPopUpMetadataRules()
    {
        $storeId = 2;
        $customerGroupId = 3;
        $itemMock = $this->createPartialMock(AbstractItem::class, ['getQuote', 'getAddress', 'getOptionByCode']);
        $addressMock = $this->createPartialMock(Address::class, []);
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);

        $this->ruleBuilderMock->expects($this->once())
            ->method('getActiveRules')
            ->with($storeId, $customerGroupId)
            ->willReturn([$ruleMock]);

        $this->validatorMock->expects($this->once())
            ->method('getValidRules')
            ->with([$ruleMock], $addressMock, [$itemMock])
            ->willReturn([$ruleMock]);

        $ruleMockMetadata = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $this->converterMock->expects($this->once())
            ->method('convert')
            ->with([$ruleMock], [$itemMock], Pool::POPUP_PROCESSOR)
            ->willReturn([$ruleMockMetadata]);

        $this->model->getPopUpMetadataRules($storeId, $customerGroupId, $addressMock, [$itemMock]);
    }

    /**
     * Test for getDiscountMetadataRules method
     *
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testGetDiscountMetadataRules()
    {
        $storeId = 3;
        $customerGroupId = 5;
        $itemMock = $this->createPartialMock(AbstractItem::class, ['getQuote', 'getAddress', 'getOptionByCode']);
        $addressMock = $this->createPartialMock(Address::class, []);
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);

        $this->ruleBuilderMock->expects($this->once())
            ->method('getActiveRules')
            ->with($storeId, $customerGroupId)
            ->willReturn([$ruleMock]);

        $this->validatorMock->expects($this->once())
            ->method('getValidRules')
            ->with([$ruleMock], $addressMock, [$itemMock])
            ->willReturn([$ruleMock]);

        $ruleMockMetadata = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $this->converterMock->expects($this->once())
            ->method('convert')
            ->with([$ruleMock], [$itemMock], Pool::DISCOUNT)
            ->willReturn([$ruleMockMetadata]);

        $this->model->getDiscountMetadataRules($storeId, $customerGroupId, $addressMock, [$itemMock]);
    }

    /**
     * Test for isValidCoupon method
     *
     * @param bool $expected
     * @param RuleInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $rulesMock
     * @param RuleInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $validRulesMock
     * @dataProvider getPromoProductQtyDataProvider
     * @throws \Exception
     */
    public function testIsValidCoupon($expected, $rulesMock, $validRulesMock)
    {
        $couponCode = 'coupon';
        $storeId = 1;
        $customerGroupId = 5;
        $itemMock = $this->createPartialMock(AbstractItem::class, ['getQuote', 'getAddress', 'getOptionByCode']);
        $addressMock = $this->createPartialMock(Address::class, []);

        $this->ruleBuilderMock->expects($this->once())
            ->method('getActiveRules')
            ->with($storeId, $customerGroupId)
            ->willReturn($rulesMock);

        if (null !== $expected) {
            $this->validatorMock->expects($this->once())
                ->method('getValidRules')
                ->with($rulesMock, $addressMock, [$itemMock])
                ->willReturn($validRulesMock);
        }

        $this->assertSame(
            $expected,
            $this->model->isValidCoupon($couponCode, $storeId, $customerGroupId, $addressMock, [$itemMock])
        );
    }

    /**
     * Data provider for testIsValid method
     */
    public function getPromoProductQtyDataProvider()
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $ruleMock->expects($this->exactly(2))
            ->method('getScenario')
            ->willReturn(Scenario::COUPON);
        $ruleMock->expects($this->exactly(2))
            ->method('getCouponCode')
            ->willReturn('coupon');

        $ruleMock1 = $this->getMockForAbstractClass(RuleInterface::class);
        $ruleMock1->expects($this->exactly(1))
            ->method('getScenario')
            ->willReturn(Scenario::BUY_X_GET_Y);

        $ruleMock2 = $this->getMockForAbstractClass(RuleInterface::class);
        $ruleMock2->expects($this->once())
            ->method('getScenario')
            ->willReturn(Scenario::COUPON);
        $ruleMock2->expects($this->once())
            ->method('getCouponCode')
            ->willReturn('coupon');

        return [
            [true, [$ruleMock], [$ruleMock]],
            [null, [$ruleMock1], []],
            [false, [$ruleMock2], []]
        ];
    }

    /**
     * Prepare rule builder
     */
    private function prepareRuleBuilder()
    {
        $this->ruleBuilderMock->expects($this->once())
            ->method('getSearchCriteriaBuilder')
            ->willReturn($this->searchCriteriaMock);
    }
}
