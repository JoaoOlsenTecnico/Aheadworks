<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\CustomerData;

use Aheadworks\Afptc\Api\Data\PromoOfferRenderInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\CustomerData\PromoOffer;
use Magento\Quote\Model\Quote;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Api\PromoOfferRenderListInterface;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\EntityManager\Hydrator;

/**
 * Class PromoOfferTest
 * @package Aheadworks\Afptc\Test\Unit\CustomerData
 */
class PromoOfferTest extends TestCase
{
    /**
     * @var PromoOffer
     */
    private $object;

    /**
     * @var RuleManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleManagementMock;

    /**
     * @var CheckoutSession|\PHPUnit_Framework_MockObject_MockObject
     */
    private $checkoutSessionMock;

    /**
     * @var PromoOfferRenderListInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $promoOfferRenderListMock;

    /**
     * @var Hydrator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $hydratorMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->ruleManagementMock = $this->getMockForAbstractClass(RuleManagementInterface::class);
        $this->checkoutSessionMock = $this->createPartialMock(CheckoutSession::class, ['getQuote']);
        $this->promoOfferRenderListMock = $this->getMockForAbstractClass(PromoOfferRenderListInterface::class);
        $this->hydratorMock = $this->createPartialMock(Hydrator::class, ['extract']);
        $this->object = $objectManager->getObject(
            PromoOffer::class,
            [
                'ruleManagement' => $this->ruleManagementMock,
                'checkoutSession' => $this->checkoutSessionMock,
                'promoOfferRenderList' => $this->promoOfferRenderListMock,
                'hydrator' => $this->hydratorMock
            ]
        );
    }

    /**
     * Test getSectionData method
     */
    public function testGetSectionData()
    {
        $quoteId = 1;
        $storeId = 1;
        $quoteMock = $this->createPartialMock(Quote::class, ['getStore', 'getId']);
        $storeMock = $this->getMockForAbstractClass(
            StoreInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getStoreId']
        );
        $metadataRulesMock = [$this->getMockForAbstractClass(RuleMetadataInterface::class)];
        $promoOfferMock = $this->getMockForAbstractClass(PromoOfferRenderInterface::class);
        $expected = [];

        $this->checkoutSessionMock->expects($this->once())
            ->method('getQuote')
            ->willReturn($quoteMock);
        $quoteMock->expects($this->once())
            ->method('getId')
            ->willReturn($quoteId);
        $this->ruleManagementMock->expects($this->once())
            ->method('getPopUpMetadataRules')
            ->with($quoteId)
            ->willReturn($metadataRulesMock);
        $quoteMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);
        $storeMock->expects($this->once())
            ->method('getStoreId')
            ->willReturn($storeId);

        $this->promoOfferRenderListMock->expects($this->once())
            ->method('getList')
            ->with($metadataRulesMock, $storeId)
            ->willReturn($promoOfferMock);
        $this->hydratorMock->expects($this->once())
            ->method('extract')
            ->with($promoOfferMock)
            ->willReturn($expected);

        $this->assertTrue(is_array($this->object->getSectionData()));
    }
}
