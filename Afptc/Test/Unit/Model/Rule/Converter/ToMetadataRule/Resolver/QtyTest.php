<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Converter\ToMetadataRule\Resolver;

use Aheadworks\Afptc\Api\Data\CartItemRuleInterface;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\ScenarioPool;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProductPool;
use Aheadworks\Afptc\Model\Rule\RuleMetadataManager;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Metadata\Rule as RuleMetadata;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty as QtyConverter;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\ScenarioInterface;
use Aheadworks\Afptc\Model\Source\Rule\Scenario;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProductInterface;
use Magento\Quote\Api\Data\CartItemExtensionInterface;

/**
 * Class QtyTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Converter\ToMetadataRule\Resolver
 */
class QtyTest extends TestCase
{
    /**
     * @var QtyConverter
     */
    private $model;

    /**
     * @var RuleMetadataManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleMetadataManagerMock;

    /**
     * @var ProductRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productRepositoryMock;

    /**
     * @var StockProductPool|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stockProductPoolMock;

    /**
     * @var ScenarioPool|\PHPUnit_Framework_MockObject_MockObject
     */
    private $scenarioPoolMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->ruleMetadataManagerMock = $this->createPartialMock(
            RuleMetadataManager::class,
            ['getPromoProductQty']
        );
        $this->productRepositoryMock = $this->getMockForAbstractClass(ProductRepositoryInterface::class);
        $this->stockProductPoolMock = $this->createPartialMock(
            StockProductPool::class,
            ['getStockProduct']
        );
        $this->scenarioPoolMock = $this->createPartialMock(
            ScenarioPool::class,
            ['getScenarioProcessor']
        );
        $this->model = $objectManager->getObject(
            QtyConverter::class,
            [
                'ruleMetadataManager' => $this->ruleMetadataManagerMock,
                'productRepository' => $this->productRepositoryMock,
                'stockProductPool' => $this->stockProductPoolMock,
                'scenarioPool' => $this->scenarioPoolMock
            ]
        );
    }

    /**
     * Test for resolveQtyToDiscountByRule method
     */
    public function testResolveQtyToDiscountByRule()
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $itemMock = $this->getMockForAbstractClass(AbstractItem::class, [], '', false, false, true, []);
        $processorMock = $this->getMockForAbstractClass(ScenarioInterface::class);
        $qty = 2;
        $ruleMock->expects($this->once())
            ->method('getScenario')
            ->willReturn(Scenario::COUPON);
        $this->scenarioPoolMock->expects($this->once())
            ->method('getScenarioProcessor')
            ->with(Scenario::COUPON)
            ->willReturn($processorMock);
        $processorMock->expects($this->once())
            ->method('getQtyToDiscountByRule')
            ->with($ruleMock, [$itemMock])
            ->willReturn($qty);

        $this->assertSame($qty, $this->model->resolveQtyToDiscountByRule($ruleMock, [$itemMock]));
    }

    /**
     * Test for resolveQtyToDiscountByStock method
     *
     * @dataProvider resolveQtyToDiscountByStockDataProvider
     * @param bool $isInStockOffer
     * @param bool $backOrderAvail
     * @param bool $isManageStock
     * @param int $result
     */
    public function testResolveQtyToDiscountByStock($isInStockOffer, $backOrderAvail, $isManageStock, $result)
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $promoProductMock = $this->getMockForAbstractClass(RuleMetadataPromoProductInterface::class);

        $storeId = 1;
        $productSku = 'sku1';
        $ruleMock->expects($this->once())
            ->method('getStoreId')
            ->willReturn($storeId);
        $promoProductMock->expects($this->once())
            ->method('getProductSku')
            ->willReturn($productSku);
        $productMock = $this->getMockForAbstractClass(ProductInterface::class);
        $this->productRepositoryMock->expects($this->once())
            ->method('get')
            ->with($productSku, false, $storeId)
            ->willReturn($productMock);
        $productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn('configurable');
        $stockProductMock = $this->getMockForAbstractClass(StockProductInterface::class);
        $this->stockProductPoolMock->expects($this->once())
            ->method('getStockProduct')
            ->with('configurable')
            ->willReturn($stockProductMock);

        $ruleMock->expects($this->any())
            ->method('isInStockOfferOnly')
            ->willReturn($isInStockOffer);
        $stockProductMock->expects($this->any())
            ->method('isBackOrderAvailable')
            ->with($promoProductMock, $storeId)
            ->willReturn($backOrderAvail);
        $stockProductMock->expects($this->any())
            ->method('isManageStockEnabled')
            ->with($promoProductMock, $storeId)
            ->willReturn($isManageStock);

        $promoProductMock->expects($this->any())
            ->method('getQty')
            ->willReturn(4);
        $stockProductMock->expects($this->any())
            ->method('getAvailableQty')
            ->with($promoProductMock, $storeId)
            ->willReturn(3);

        $this->assertSame($result, $this->model->resolveQtyToDiscountByStock($promoProductMock, $ruleMock));
    }

    /**
     * Data provider for testResolveQtyToDiscountByStock method
     */
    public function resolveQtyToDiscountByStockDataProvider()
    {
        return [
            [true, true, true, 3],
            [false, true, false, 4],
            [false, false, false, 4],
            [false, true, true, 4],
            [false, false, true, 3],
        ];
    }

    /**
     * Test for resolveQtyItemByMetadataRules method
     */
    public function testResolveQtyItemByMetadataRules()
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $itemMock = $this->getMockForAbstractClass(
            AbstractItem::class,
            [],
            '',
            false,
            false,
            true,
            ['getTotalQty', 'getAwAfptcId']
        );
        $ruleId = 1;
        $ruleMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn($ruleId);
        $ruleMetadataMock = $this->createPartialMock(RuleMetadata::class, []);
        $itemMock->expects($this->once())
            ->method('getAwAfptcId')
            ->willReturn($ruleId);

        $this->ruleMetadataManagerMock->expects($this->once())
            ->method('getPromoProductQty')
            ->with([$ruleMetadataMock], $ruleId)
            ->willReturn(1);

        $itemMock->expects($this->once())
            ->method('getTotalQty')
            ->willReturn(5);

        $this->assertSame(
            0,
            $this->model->resolveQtyItemByMetadataRules(
                [$ruleMetadataMock],
                $ruleMock,
                $itemMock,
                5
            )
        );
    }

    /**
     * Test for resolveQtyToGive method
     */
    public function testResolveQtyToGive()
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $itemMock = $this->getMockForAbstractClass(AbstractItem::class, [], '', false, false, true, []);
        $ruleId = 1;
        $ruleMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn($ruleId);
        $ruleMock->expects($this->once())
            ->method('getQtyToGive')
            ->willReturn(2);

        $this->assertSame(8, $this->model->resolveQtyToGive($ruleMock, 4, [$itemMock]));
    }

    /**
     * Test for getAppliedQty method
     */
    public function testGetAppliedQty()
    {
        $itemMock = $this->getMockForAbstractClass(
            AbstractItem::class,
            [],
            '',
            false,
            false,
            true,
            ['getExtensionAttributes']
        );
        $ruleId = 1;
        $extensionAttrMock = $this->getMockForAbstractClass(
            CartItemExtensionInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getAwAfptcRules']
        );
        $itemMock->expects($this->exactly(3))
            ->method('getExtensionAttributes')
            ->willReturn($extensionAttrMock);
        $cartRuleMock = $this->getMockForAbstractClass(CartItemRuleInterface::class);
        $extensionAttrMock->expects($this->exactly(2))
            ->method('getAwAfptcRules')
            ->willReturn([$cartRuleMock]);
        $cartRuleMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn($ruleId);
        $qty = 8;
        $cartRuleMock->expects($this->once())
            ->method('getQty')
            ->willReturn($qty);

        $this->assertSame($qty, $this->model->getAppliedQty($itemMock, $ruleId));
    }

    /**
     * Test for getAppliedQty method
     */
    public function testGetRequestQty()
    {
        $itemMock = $this->getMockForAbstractClass(
            AbstractItem::class,
            [],
            '',
            false,
            false,
            true,
            ['getExtensionAttributes']
        );
        $ruleId = 1;
        $extensionAttrMock = $this->getMockForAbstractClass(
            CartItemExtensionInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getAwAfptcRulesRequest']
        );
        $itemMock->expects($this->exactly(2))
            ->method('getExtensionAttributes')
            ->willReturn($extensionAttrMock);
        $cartRuleMock = $this->getMockForAbstractClass(CartItemRuleInterface::class);
        $extensionAttrMock->expects($this->once())
            ->method('getAwAfptcRulesRequest')
            ->willReturn([$cartRuleMock]);
        $cartRuleMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn(2);

        $this->assertSame(0, $this->model->getRequestQty($itemMock, $ruleId));
    }
}
