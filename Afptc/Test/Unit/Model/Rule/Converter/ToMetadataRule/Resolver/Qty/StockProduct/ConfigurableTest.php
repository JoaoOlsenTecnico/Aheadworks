<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProduct;

use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\CatalogInventory\Model\Stock;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule\PromoProduct\Stock\Configurable as ConfigurableStockResource;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProduct\Configurable;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Quote\Api\Data\ProductOptionInterface;
use Magento\Quote\Api\Data\ProductOptionExtensionInterface;

/**
 * Class ConfigurableTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProduct
 */
class ConfigurableTest extends TestCase
{
    /**
     * @var Configurable
     */
    private $model;

    /**
     * @var StockRegistryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stockRegistryMock;

    /**
     * @var ProductRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productRepositoryMock;

    /**
     * @var ConfigurableStockResource|\PHPUnit_Framework_MockObject_MockObject
     */
    private $configurableResourceMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->stockRegistryMock = $this->getMockForAbstractClass(StockRegistryInterface::class);
        $this->productRepositoryMock = $this->getMockForAbstractClass(ProductRepositoryInterface::class);
        $this->configurableResourceMock = $this->createPartialMock(ConfigurableStockResource::class, ['getStockQty']);
        $this->model = $objectManager->getObject(
            Configurable::class,
            [
                'stockRegistry' => $this->stockRegistryMock,
                'productRepository' => $this->productRepositoryMock,
                'configurableResource' => $this->configurableResourceMock
            ]
        );
    }

    /**
     * Test for getAvailableQty method
     *
     * @dataProvider getAvailableQtyDataProvider
     * @param string $isInStock
     * @param ProductOptionInterface|null $optionMock
     * @param int $result
     */
    public function testGetAvailableQty($isInStock, $optionMock, $result)
    {
        $ruleMetadataPromoProductMock = $this->getMockForAbstractClass(RuleMetadataPromoProductInterface::class);
        $sku = 'test-sku';
        $storeId = 2;
        $ruleMetadataPromoProductMock->expects($this->once())
            ->method('getProductSku')
            ->willReturn($sku);
        $stockItemMock = $this->getMockForAbstractClass(StockItemInterface::class);
        $this->stockRegistryMock->expects($this->once())
            ->method('getStockItemBySku')
            ->with($sku, $storeId)
            ->willReturn($stockItemMock);
        $stockItemMock->expects($this->once())
            ->method('getIsInStock')
            ->willReturn($isInStock);

        if ($isInStock == Stock::STOCK_IN_STOCK) {
            $ruleMetadataPromoProductMock->expects($this->any())
                ->method('getOption')
                ->willReturn($optionMock);
            $ruleMetadataPromoProductMock->expects($this->once())
                ->method('getQty')
                ->willReturn(8);
        }

        $this->assertSame($result, $this->model->getAvailableQty($ruleMetadataPromoProductMock, $storeId));
    }

    /**
     * Data provider for getAvailableQty method
     */
    public function getAvailableQtyDataProvider()
    {
        $optionMock = $this->getMockForAbstractClass(ProductOptionInterface::class);
        $extAttrMock = $this->getMockForAbstractClass(
            ProductOptionExtensionInterface::class,
            [],
            '',
            false,
            false,
            true,
            ['getConfigurableItemOptions']
        );
        $optionMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($extAttrMock);
        $configurableItemOptionsMock = ['some_options'];
        $extAttrMock->expects($this->once())
            ->method('getConfigurableItemOptions')
            ->willReturn($configurableItemOptionsMock);

        return [
            [Stock::STOCK_OUT_OF_STOCK, null, 0],
            [Stock::STOCK_IN_STOCK, null, 8],
        ];
    }

    /**
     * Test for isManageStockEnabled method
     */
    public function testIsManageStockEnabled()
    {
        $ruleMetadataPromoProductMock = $this->getMockForAbstractClass(RuleMetadataPromoProductInterface::class);
        $sku = 'test-sku';
        $scopeId = 2;
        $ruleMetadataPromoProductMock->expects($this->once())
            ->method('getProductSku')
            ->willReturn($sku);
        $stockItemMock = $this->getMockForAbstractClass(StockItemInterface::class);
        $this->stockRegistryMock->expects($this->once())
            ->method('getStockItemBySku')
            ->with($sku, $scopeId)
            ->willReturn($stockItemMock);
        $stockItemMock->expects($this->once())
            ->method('getManageStock')
            ->willReturn(true);

        $this->assertSame(true, $this->model->isManageStockEnabled($ruleMetadataPromoProductMock, $scopeId));
    }

    /**
     * Test for isManageStockEnabled method on exception
     */
    public function testIsManageStockEnabledOnException()
    {
        $ruleMetadataPromoProductMock = $this->getMockForAbstractClass(RuleMetadataPromoProductInterface::class);
        $sku = 'test-sku';
        $scopeId = 2;
        $ruleMetadataPromoProductMock->expects($this->once())
            ->method('getProductSku')
            ->willReturn($sku);
        $exception = new NoSuchEntityException(__('some exception'));
        $this->stockRegistryMock->expects($this->once())
            ->method('getStockItemBySku')
            ->with($sku, $scopeId)
            ->willThrowException($exception);

        $this->assertSame(false, $this->model->isManageStockEnabled($ruleMetadataPromoProductMock, $scopeId));
    }
}
