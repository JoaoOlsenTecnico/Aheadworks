<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Promo\Renderer\Product;

use Aheadworks\Afptc\Model\Source\Rule\Promo\Renderer\Placement;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Registry;
use Aheadworks\Afptc\Model\Rule\Promo\Renderer\Product\Resolver;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class ResolverTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Promo\Renderer\Product
 */
class ResolverTest extends TestCase
{
    /**
     * @var Resolver
     */
    private $model;

    /**
     * @var Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    private $registryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->registryMock = $this->createPartialMock(Registry::class, ['registry']);
        $this->model = $objectManager->getObject(
            Resolver::class,
            [
                'registry' => $this->registryMock
            ]
        );
    }

    /**
     * Test for resolveByPlacement method
     */
    public function testResolveByPlacement()
    {
        $productMock = $this->getMockForAbstractClass(ProductInterface::class);
        $this->registryMock->expects($this->once())
            ->method('registry')
            ->willReturn($productMock);
        $this->assertEquals($productMock, $this->model->resolveByPlacement(Placement::PRODUCT_PAGE));
    }

    /**
     * Test for resolveByPlacement method with non-existing position
     */
    public function testResolveByPlacementWithWrongPosition()
    {
        $productMock = $this->getMockForAbstractClass(ProductInterface::class);
        $this->registryMock->expects($this->never())
            ->method('registry')
            ->willReturn($productMock);
        $this->assertEquals(null, $this->model->resolveByPlacement('non-existing-position'));
    }
}
