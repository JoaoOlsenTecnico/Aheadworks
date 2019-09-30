<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule;

use Aheadworks\Afptc\Model\Rule\RuleMetadataManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;

/**
 * Class RuleMetadataManagerTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule
 */
class RuleMetadataManagerTest extends TestCase
{
    /**
     * @var RuleMetadataManager
     */
    private $model;

    /**#@+
     * Constants defined testing
     */
    const PRODUCT_UNIQUE_KEY = 'key1';
    const PRODUCT_QTY = 5;
    /**#@-*/

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(RuleMetadataManager::class);
    }

    /**
     * Test for isValid method
     *
     * @dataProvider getPromoProductQtyDataProvider
     * @param RuleMetadataInterface|RuleMetadataInterface[] $metadataRules,
     * @param string $uniqueKey
     * @param bool $result
     */
    public function testGetPromoProductQty($metadataRules, $uniqueKey, $result)
    {
        $this->assertSame($result, $this->model->getPromoProductQty($metadataRules, $uniqueKey));
    }

    /**
     * Data provider for testIsValid method
     */
    public function getPromoProductQtyDataProvider()
    {
        $metaDataRule1 = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $metaDataRule2 = $this->getMockForAbstractClass(RuleMetadataInterface::class);

        $metaDataRule1->expects($this->any())
            ->method('getPromoProducts')
            ->willReturn([$this->getPromoProduct()]);

        $metaDataRule2->expects($this->any())
            ->method('getPromoProducts')
            ->willReturn([$this->getRandomProduct()]);

        return [
            '1 metadata rule' => [[$metaDataRule1], self::PRODUCT_UNIQUE_KEY, self::PRODUCT_QTY],
            '2 metadata rules' => [[$metaDataRule1, $metaDataRule2], self::PRODUCT_UNIQUE_KEY, self::PRODUCT_QTY],
            '1 metadata passed as object ' => [$metaDataRule1, self::PRODUCT_UNIQUE_KEY, self::PRODUCT_QTY],
            'empty metadata are passed ' => [[], self::PRODUCT_UNIQUE_KEY, 0],
        ];
    }

    /**
     * Generate promo product with unique key
     *
     * @return RuleMetadataPromoProductInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getPromoProduct()
    {
        $promoProduct =  $this->getMockForAbstractClass(RuleMetadataPromoProductInterface::class);
        $promoProduct->expects($this->any())
            ->method('getUniqueKey')
            ->willReturn(self::PRODUCT_UNIQUE_KEY);
        $promoProduct->expects($this->any())
            ->method('getQty')
            ->willReturn(self::PRODUCT_QTY);

        return $promoProduct;
    }

    /**
     * Generate random product
     *
     * @return RuleMetadataPromoProductInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getRandomProduct()
    {
        $promoProduct =  $this->getMockForAbstractClass(RuleMetadataPromoProductInterface::class);
        $promoProduct->expects($this->any())
            ->method('getUniqueKey')
            ->willReturn('some key');
        $promoProduct->expects($this->any())
            ->method('getQty')
            ->willReturn(1);

        return $promoProduct;
    }

    /**
     * Test for isPromoProduct method with promo product
     */
    public function testIsPromoProductWithPromoProduct()
    {
        /** @var RuleMetadataInterface|\PHPUnit_Framework_MockObject_MockObject $metaDataRule */
        $metaDataRule = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $metaDataRule->expects($this->once())
            ->method('getPromoProducts')
            ->willReturn([$this->getPromoProduct()]);

        $this->assertSame(true, $this->model->isPromoProduct($metaDataRule, self::PRODUCT_UNIQUE_KEY));
    }

    /**
     * Test for isPromoProduct method with random product
     */
    public function testIsPromoProductWithRandomProduct()
    {
        /** @var RuleMetadataInterface|\PHPUnit_Framework_MockObject_MockObject $metaDataRule */
        $metaDataRule = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $metaDataRule->expects($this->once())
            ->method('getPromoProducts')
            ->willReturn([$this->getRandomProduct()]);

        $this->assertSame(false, $this->model->isPromoProduct($metaDataRule, self::PRODUCT_UNIQUE_KEY));
    }
}
