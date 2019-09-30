<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Cart\Converter\ToCartItem;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Model\Cart\Converter\ToCartItem\Validator;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class ValidatorTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Cart\Converter\ToCartItem
 */
class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    private $model;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(
            Validator::class,
            []
        );
    }

    /**
     * Test isValid method
     *
     * @param bool $expected
     * @param float $availableQtyToGive
     * @param float $promoProductQty
     * @dataProvider isValidDataProvider
     */
    public function testIsValid($expected, $availableQtyToGive, $promoProductQty)
    {
        $availableQtyToGive = 1;
        $metadataRuleMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $promoProduct = $this->getMockForAbstractClass(RuleMetadataPromoProductInterface::class);
        $promoProduct->expects($this->once())
            ->method('getQty')
            ->willReturn($promoProductQty);

        $metadataRuleMock->expects($this->once())
            ->method('getPromoProducts')
            ->willReturn([$promoProduct]);
        $metadataRuleMock->expects($this->once())
            ->method('getAvailableQtyToGive')
            ->willReturn($availableQtyToGive);

        $this->assertEquals($expected, $this->model->isValid($metadataRuleMock));
    }

    /**
     * Data provider for isValid test
     *
     * @return array
     */
    public function isValidDataProvider()
    {
        return [
            [true, 1, 1],
            [true, 2, 1],
            [false, 1, 2]
        ];
    }
}
