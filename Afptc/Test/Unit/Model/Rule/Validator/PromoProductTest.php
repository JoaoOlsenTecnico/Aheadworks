<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Validator;

use Aheadworks\Afptc\Model\Rule\Validator\PromoProduct;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class PromoProductTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Validator
 */
class PromoProductTest extends TestCase
{
    /**
     * @var PromoProduct
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
        $this->model = $objectManager->getObject(PromoProduct::class);
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
        $promoProducts = ['product1', 'product2'];

        $validRuleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $validRuleMock->expects($this->once())
            ->method('getQtyToGive')
            ->willReturn(1);
        $validRuleMock->expects($this->once())
            ->method('getPromoProducts')
            ->willReturn($promoProducts);

        $invalidRuleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $invalidRuleMock->expects($this->once())
            ->method('getQtyToGive')
            ->willReturn(3);
        $invalidRuleMock->expects($this->once())
            ->method('getPromoProducts')
            ->willReturn($promoProducts);

        return [
            'valid' => [$validRuleMock, true],
            'invalid' => [$invalidRuleMock, false],
        ];
    }
}
