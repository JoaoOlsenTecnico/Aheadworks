<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Ui\DataProvider\PromoOffer\RenderList\Product;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterface;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\ListingBuilder;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Composite as ProductProcessor;
use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class ListingBuilderTest
 * @package Aheadworks\Afptc\Test\Unit\Ui\DataProvider\PromoOffer\RenderList\Product
 */
class ListingBuilderTest extends TestCase
{
    /**
     * @var ListingBuilder
     */
    private $component;

    /**
     * @var ProductProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productProcessorMock;

    /**
     * @var ProductRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productRepositoryMock;

    /**
     * @var ProductRenderInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productRenderFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->productProcessorMock = $this->createPartialMock(ProductProcessor::class, ['prepareProductData']);
        $this->productRepositoryMock = $this->getMockForAbstractClass(ProductRepositoryInterface::class);
        $this->productRenderFactoryMock = $this->createPartialMock(ProductRenderInterfaceFactory::class, ['create']);

        $this->component = $objectManager->getObject(
            ListingBuilder::class,
            [
                'productProcessor' => $this->productProcessorMock,
                'productRepository' => $this->productRepositoryMock,
                'productRenderFactory' => $this->productRenderFactoryMock,
            ]
        );
    }

    /**
     * Test build method
     *
     * @dataProvider buildDataProvider
     */
    public function testBuild($isValid)
    {
        $expected = [];
        $productSku = 'sku';
        $storeId = 1;
        $currentCurrencyCode = 'USD';
        $ruleId = 1;
        $exception = new NoSuchEntityException(__('Exception'));
        $metadataRuleMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $storeMock = $this->createPartialMock(Store::class, ['getId', 'getCurrentCurrencyCode']);
        $promoProductMock = $this->getMockForAbstractClass(RuleMetadataPromoProductInterface::class);
        $productMock = $this->getMockForAbstractClass(ProductInterface::class);
        $productRenderMock = $this->getMockForAbstractClass(ProductRenderInterface::class);
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);

        $metadataRuleMock->expects($this->once())
            ->method('getPromoProducts')
            ->willReturn([$promoProductMock]);
        $promoProductMock->expects($this->once())
            ->method('getProductSku')
            ->willReturn($productSku);

        if ($isValid) {
            $this->productRepositoryMock->expects($this->once())
                ->method('get')
                ->with($productSku)
                ->willReturn($productMock);

            $this->productRenderFactoryMock->expects($this->once())
                ->method('create')
                ->willReturn($productRenderMock);
            $storeMock->expects($this->once())
                ->method('getId')
                ->willReturn($storeId);
            $storeMock->expects($this->once())
                ->method('getCurrentCurrencyCode')
                ->willReturn($currentCurrencyCode);
            $metadataRuleMock->expects($this->once())
                ->method('getRule')
                ->willReturn($ruleMock);
            $ruleMock->expects($this->once())
                ->method('getRuleId')
                ->willReturn($ruleId);

            $productRenderMock->expects($this->once())
                ->method('setStoreId')
                ->with($storeId)
                ->willReturnSelf();
            $productRenderMock->expects($this->once())
                ->method('setCurrencyCode')
                ->with($currentCurrencyCode)
                ->willReturnSelf();
            $productRenderMock->expects($this->once())
                ->method('setRuleId')
                ->with($ruleId)
                ->willReturnSelf();

            $this->productProcessorMock->expects($this->once())
                ->method('prepareProductData')
                ->with($productMock, $metadataRuleMock, $promoProductMock, $productRenderMock);
            $expected = [$productRenderMock];
        } else {
            $this->productRepositoryMock->expects($this->once())
                ->method('get')
                ->with($productSku)
                ->willThrowException($exception);
        }

        $this->assertEquals($expected, $this->component->build($metadataRuleMock, $storeMock));
    }

    /**
     * Data provider for build test
     *
     * @return array
     */
    public function buildDataProvider()
    {
        return [
            [true],
            [false]
        ];
    }
}
