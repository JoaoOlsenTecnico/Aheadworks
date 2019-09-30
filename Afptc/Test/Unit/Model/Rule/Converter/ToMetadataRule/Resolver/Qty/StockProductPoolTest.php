<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Converter\ToMetadataRule\Resolver\Qty;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProductPool;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProductInterface;

/**
 * Class StockProductPoolTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Converter\ToMetadataRule\Resolver\Qty
 */
class StockProductPoolTest extends TestCase
{
    /**
     * @var StockProductPool
     */
    private $model;

    /**
     * @var StockProductInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stockProductMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->stockProductMock = $this->getMockForAbstractClass(StockProductInterface::class);
        $this->model = $objectManager->getObject(
            StockProductPool::class,
            [
                'stockProducts' => [
                    'default' => $this->stockProductMock
                ]
            ]
        );
    }

    /**
     * Test for getStockProduct method
     */
    public function testGetStockProduct()
    {
        $type = 'default';
        $this->assertSame($this->stockProductMock, $this->model->getStockProduct($type));
    }

    /**
     * Test for getStockProduct method with non-existing type
     */
    public function getStockProductWithNonExistingType()
    {
        $type = 'non-existing-type';
        $this->model->getStockProduct($type);
    }
}
