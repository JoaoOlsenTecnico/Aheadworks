<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer;

use Aheadworks\Afptc\Model\Rule\PromoOffer\Validator\Pool as ValidatorPool;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Validator;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Validator\ValidatorInterface;

/**
 * Class ValidatorTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer
 */
class ValidatorTest extends TestCase
{
    /**
     * @var ValidatorPool|\PHPUnit_Framework_MockObject_MockObject
     */
    private $validatorPoolMock;

    /**
     * @var Validator
     */
    private $model;

    /**
     * @var ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $validatorMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->validatorMock = $this->createPartialMock(ValidatorInterface::class, ['isValidItems', 'isValidRule']);
        $this->validatorPoolMock = $this->createPartialMock(ValidatorPool::class, ['getValidator']);
        $this->validatorPoolMock->expects($this->any())
            ->method('getValidator')
            ->willReturn($this->validatorMock);

        $this->model = $objectManager->getObject(
            Validator::class,
            [
                'validatorPool' => $this->validatorPoolMock,
            ]
        );
    }

    /**
     * test for getValidRules method in case items are invalid
     *
     * @throws \Exception
     */
    public function testGetValidRulesWithInvalidItems()
    {
        list($ruleMock1, $itemMock1, $addressMock,$lastQuoteItemMock) = $this->getInitialData();
        $ruleMock2 = $this->getMockForAbstractClass(RuleInterface::class);

        $this->validatorMock->expects($this->any())
            ->method('isValidItems')
            ->with([$itemMock1])
            ->willReturn(false);
        $this->validatorMock->expects($this->any())
            ->method('isValidRule')
            ->willReturn(false);

        $this->assertSame(
            [],
            $this->model->getValidRules([$ruleMock1, $ruleMock2], $addressMock, [$itemMock1], $lastQuoteItemMock)
        );
    }

    /**
     * test for getValidRules method in case rule is invalid
     *
     * @throws \Exception
     */
    public function testGetValidRulesWithInvalidRule()
    {
        list($ruleMock1, $itemMock1, $addressMock,$lastQuoteItemMock) = $this->getInitialData();

        $this->validatorMock->expects($this->any())
            ->method('isValidItems')
            ->with([$itemMock1])
            ->willReturn(true);
        $this->validatorMock->expects($this->any())
            ->method('isValidRule')
            ->willReturn(false);

        $this->assertSame(
            [],
            $this->model->getValidRules([$ruleMock1], $addressMock, [$itemMock1], $lastQuoteItemMock)
        );
    }

    /**
     * test for getValidRules method in case all rules are valid
     *
     * @throws \Exception
     */
    public function testGetValidRulesWithAllValid()
    {
        list($ruleMock1, $itemMock1, $addressMock,$lastQuoteItemMock) = $this->getInitialData();
        $ruleMock2 = $this->getMockForAbstractClass(RuleInterface::class);
        $ruleMock3 = $this->getMockForAbstractClass(RuleInterface::class);

        $this->validatorMock->expects($this->any())
            ->method('isValidItems')
            ->with([$itemMock1])
            ->willReturn(true);
        $this->validatorMock->expects($this->any())
            ->method('isValidRule')
            ->willReturn(true);

        $this->assertSame(
            [$ruleMock1, $ruleMock2, $ruleMock3],
            $this->model->getValidRules(
                [$ruleMock1, $ruleMock2, $ruleMock3],
                $addressMock,
                [$itemMock1],
                $lastQuoteItemMock
            )
        );
    }

    /**
     * test for getValidRules method in case second rule stop processing
     *
     * @throws \Exception
     */
    public function testGetValidRulesWithSecondRuleStopProcessing()
    {
        list($ruleMock1, $itemMock1, $addressMock,$lastQuoteItemMock) = $this->getInitialData();
        $ruleMock2 = $this->getMockForAbstractClass(RuleInterface::class);
        $ruleMock3 = $this->getMockForAbstractClass(RuleInterface::class);

        $this->validatorMock->expects($this->any())
            ->method('isValidItems')
            ->with([$itemMock1])
            ->willReturn(true);
        $this->validatorMock->expects($this->any())
            ->method('isValidRule')
            ->willReturn(true);

        $ruleMock2->expects($this->any())
            ->method('isStopRulesProcessing')
            ->willReturn(true);

        $this->assertSame(
            [$ruleMock1, $ruleMock2],
            $this->model->getValidRules(
                [$ruleMock1, $ruleMock2, $ruleMock3],
                $addressMock,
                [$itemMock1],
                $lastQuoteItemMock
            )
        );
    }

    /**
     * test for getValidRules method on exception
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Unknown validator: scenario1 requested
     */
    public function testGetValidRulesOnException()
    {
        list($ruleMock1, $itemMock1, $addressMock,$lastQuoteItemMock) = $this->getInitialData();
        $this->validatorPoolMock->expects($this->once())
            ->method('getValidator')
            ->willThrowException(new \Exception('Unknown validator: scenario1 requested'));
        $this->model->getValidRules([$ruleMock1], $addressMock, [$itemMock1], $lastQuoteItemMock);
    }

    /**
     * Get initial data for tests
     *
     * @return array
     */
    private function getInitialData()
    {
        $ruleMock1 = $this->getMockForAbstractClass(RuleInterface::class);
        $itemMock1 = $this->createPartialMock(AbstractItem::class, ['getQuote', 'getAddress', 'getOptionByCode']);
        $addressMock = $this->createPartialMock(Address::class, []);
        $lastQuoteItemMock = $this->createPartialMock(
            AbstractItem::class,
            ['getQuote', 'getAddress', 'getOptionByCode']
        );

        return [$ruleMock1, $itemMock1, $addressMock, $lastQuoteItemMock];
    }
}
