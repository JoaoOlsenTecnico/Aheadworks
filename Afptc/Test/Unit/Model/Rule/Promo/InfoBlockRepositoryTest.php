<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Promo;

use Aheadworks\Afptc\Model\Rule\Promo\Block\Factory as PromoBlockFactory;
use Aheadworks\Afptc\Model\Rule\Promo\Block\Rule\Loader;
use Aheadworks\Afptc\Model\Rule\Promo\InfoBlockRepository;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Catalog\Api\Data\ProductInterface;
use Aheadworks\Afptc\Api\Data\PromoInfoBlockInterface;
use Aheadworks\Afptc\Api\Data\PromoInterface;

/**
 * Class InfoBlockRepositoryTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Promo
 */
class InfoBlockRepositoryTest extends TestCase
{
    /**
     * @var InfoBlockRepository
     */
    private $model;

    /**
     * @var PromoBlockFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $promoBlockFactoryMock;

    /**
     * @var Loader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loaderMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->promoBlockFactoryMock = $this->createPartialMock(PromoBlockFactory::class, ['create']);
        $this->loaderMock = $this->createPartialMock(
            Loader::class,
            ['getAvailableRuleDataForProduct', 'getPromoItems']
        );
        $this->model = $objectManager->getObject(
            InfoBlockRepository::class,
            [
                'promoBlockFactory' => $this->promoBlockFactoryMock,
                'loader' => $this->loaderMock
            ]
        );
    }

    /**
     * Test for getList method
     */
    public function testGetList()
    {
        $productMock = $this->getMockForAbstractClass(ProductInterface::class);
        $customerGroupId = 2;

        $promoBlockMock = $this->getMockForAbstractClass(PromoInfoBlockInterface::class);
        $promoItemMock = $this->getMockForAbstractClass(PromoInterface::class);
        $ruleProductData = ['some_data'];

        $this->loaderMock->expects($this->once())
            ->method('getAvailableRuleDataForProduct')
            ->with($productMock, $customerGroupId)
            ->willReturn($ruleProductData);
        $this->loaderMock->expects($this->once())
            ->method('getPromoItems')
            ->with($ruleProductData)
            ->willReturn([$promoItemMock]);
        $this->promoBlockFactoryMock->expects($this->once())
            ->method('create')
            ->with($promoItemMock)
            ->willReturn($promoBlockMock);
        $this->assertEquals([$promoBlockMock], $this->model->getList($productMock, $customerGroupId));
    }
}
