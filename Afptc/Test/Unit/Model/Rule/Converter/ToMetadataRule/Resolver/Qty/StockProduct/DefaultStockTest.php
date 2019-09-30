<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProduct;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Model\Stock;
use Magento\Framework\Exception\NoSuchEntityException;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProduct\DefaultStock;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;

/**
 * Class DefaultStockTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProduct
 */
class DefaultStockTest extends TestCase
{
    /**
     * @var DefaultStock
     */
    private $model;

    /**
     * @var StockRegistryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stockRegistryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->stockRegistryMock = $this->getMockForAbstractClass(StockRegistryInterface::class);
        $this->model = $objectManager->getObject(
            DefaultStock::class,
            [
                'stockRegistry' => $this->stockRegistryMock,
            ]
        );
    }

    /**
     * Test for getAvailableQty method
     *
     * @dataProvider getAvailableQtyDataProvider
     * @param string $isInStock
     * @param int $result
     */
    public function testGetAvailableQty($isInStock, $result)
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
            ->method('getIsInStock')
            ->willReturn($isInStock);
        if ($isInStock == Stock::STOCK_IN_STOCK) {
            $stockItemMock->expects($this->once())
                ->method('getQty')
                ->willReturn(8);
        }
        $this->assertSame($result, $this->model->getAvailableQty($ruleMetadataPromoProductMock, $scopeId));
    }

    /**
     * Data provider for getAvailableQty method
     */
    public function getAvailableQtyDataProvider()
    {
        return [
            [Stock::STOCK_IN_STOCK, 8],
            [Stock::STOCK_OUT_OF_STOCK, 0],
        ];
    }

    /**
     * Test for getAvailableQty method on exception
     */
    public function testGetAvailableQtyOnException()
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
        $this->assertSame(0, $this->model->getAvailableQty($ruleMetadataPromoProductMock, $scopeId));
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

    /**
     * Test for getAvailableQty method
     *
     * @dataProvider isBackOrderAvailableDataProvider
     * @param string $isInStock
     * @param string $getBackorders,
     * @param bool $manageStock
     * @param bool $result
     */
    public function testIsBackOrderAvailable($isInStock, $getBackorders, $manageStock, $result)
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

        $stockItemMock->expects($this->any())
            ->method('getIsInStock')
            ->willReturn($isInStock);
        $stockItemMock->expects($this->any())
            ->method('getBackorders')
            ->willReturn($getBackorders);
        $stockItemMock->expects($this->any())
            ->method('getManageStock')
            ->willReturn($manageStock);

        $this->assertSame($result, $this->model->isBackOrderAvailable($ruleMetadataPromoProductMock, $scopeId));
    }

    /**
     * Data provider for isBackOrderAvailable method
     */
    public function isBackOrderAvailableDataProvider()
    {
        return [
            [Stock::STOCK_IN_STOCK, Stock::BACKORDERS_YES_NOTIFY, true, true],
            [Stock::STOCK_IN_STOCK, Stock::BACKORDERS_NO, true, false],
            [Stock::STOCK_IN_STOCK, Stock::BACKORDERS_NO, false, false],
            [Stock::STOCK_OUT_OF_STOCK, Stock::BACKORDERS_NO, false, false],
            [Stock::STOCK_IN_STOCK, Stock::BACKORDERS_YES_NONOTIFY, false, false],
            [Stock::STOCK_IN_STOCK, Stock::BACKORDERS_YES_NONOTIFY, true, true],
        ];
    }

    /**
     * Test for getAvailableQty method on exception
     */
    public function testIsBackOrderAvailableOnException()
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

        $this->assertSame(false, $this->model->isBackOrderAvailable($ruleMetadataPromoProductMock, $scopeId));
    }
}
