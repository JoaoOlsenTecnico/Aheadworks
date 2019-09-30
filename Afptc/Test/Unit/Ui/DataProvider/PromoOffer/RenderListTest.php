<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Ui\DataProvider\PromoOffer;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigInterface;
use Aheadworks\Afptc\Api\Data\PromoOfferRenderInterface;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigInterfaceFactory;
use Aheadworks\Afptc\Api\Data\PromoOfferRenderInterfaceFactory;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\ListingBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor\Composite as RenderProcessor;

/**
 * Class RenderListTest
 * @package Aheadworks\Afptc\Test\Unit\Ui\DataProvider\PromoOffer
 */
class RenderListTest extends TestCase
{
    /**
     * @var RenderList
     */
    private $component;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagerMock;

    /**
     * @var ListingBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productListingBuilderMock;

    /**
     * @var RuleConfigInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleConfigFactoryMock;

    /**
     * @var PromoOfferRenderInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $promoOfferRenderFactoryMock;

    /**
     * @var RenderProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $processorMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->productListingBuilderMock = $this->createPartialMock(ListingBuilder::class, ['build']);
        $this->ruleConfigFactoryMock = $this->createPartialMock(RuleConfigInterfaceFactory::class, ['create']);
        $this->promoOfferRenderFactoryMock = $this->createPartialMock(
            PromoOfferRenderInterfaceFactory::class,
            ['create']
        );
        $this->processorMock = $this->createPartialMock(RenderProcessor::class, ['prepareRender']);

        $this->component = $objectManager->getObject(
            RenderList::class,
            [
                'storeManager' => $this->storeManagerMock,
                'productListingBuilder' => $this->productListingBuilderMock,
                'ruleConfigFactory' => $this->ruleConfigFactoryMock,
                'promoOfferRenderFactory' => $this->promoOfferRenderFactoryMock,
                'processor' => $this->processorMock
            ]
        );
    }

    /**
     * Test getList method
     */
    public function testGetList()
    {
        $storeId = 1;
        $ruleId = 1;
        $availableQtyToGive = 1;
        $websiteId = 1;
        $items = [];
        $storeMock = $this->getMockForAbstractClass(StoreInterface::class);
        $metadataRulesMock = [$this->getMockForAbstractClass(RuleMetadataInterface::class)];
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        $ruleConfigMock = $this->getMockForAbstractClass(RuleConfigInterface::class);
        $promoOfferRenderMock = $this->getMockForAbstractClass(PromoOfferRenderInterface::class);

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->with($storeId)
            ->willReturn($storeMock);
        $this->productListingBuilderMock->expects($this->once())
            ->method('build')
            ->with($metadataRulesMock[0], $storeMock)
            ->willReturn($items);

        $this->ruleConfigFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($ruleConfigMock);
        $metadataRulesMock[0]->expects($this->once())
            ->method('getRule')
            ->willReturn($ruleMock);
        $ruleMock->expects($this->once())
            ->method('getRuleId')
            ->willReturn($ruleId);
        $metadataRulesMock[0]->expects($this->once())
            ->method('getAvailableQtyToGive')
            ->willReturn($availableQtyToGive);

        $ruleConfigMock->expects($this->once())
            ->method('setRuleId')
            ->with($ruleId)
            ->willReturnSelf();
        $ruleConfigMock->expects($this->once())
            ->method('setQtyToGive')
            ->with($availableQtyToGive)
            ->willReturnSelf();

        $this->promoOfferRenderFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($promoOfferRenderMock);
        $storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $promoOfferRenderMock->expects($this->once())
            ->method('setWebsiteId')
            ->with($websiteId)
            ->willReturnSelf();
        $promoOfferRenderMock->expects($this->once())
            ->method('setItems')
            ->with($items)
            ->willReturnSelf();
        $promoOfferRenderMock->expects($this->once())
            ->method('setRulesConfig')
            ->with([$ruleConfigMock])
            ->willReturnSelf();

        $this->processorMock->expects($this->once())
            ->method('prepareRender')
            ->with($promoOfferRenderMock, $metadataRulesMock);

        $this->assertEquals($promoOfferRenderMock, $this->component->getList($metadataRulesMock, $storeId));
    }
}
