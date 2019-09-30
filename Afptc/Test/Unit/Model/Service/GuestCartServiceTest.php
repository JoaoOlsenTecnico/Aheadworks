<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Service;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Service\GuestCartService;
use Aheadworks\Afptc\Api\CartManagementInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class GuestCartServiceTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Service
 */
class GuestCartServiceTest extends TestCase
{
    /**
     * @var GuestCartService
     */
    private $model;

    /**
     * @var CartManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartManagementMock;

    /**
     * @var QuoteIdMaskFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $quoteIdMaskFactoryMock;

    /**
     * @var string
     */
    private $cartIdMask = 'mask';

    /**
     * @var int
     */
    private $cartId = 1;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->cartManagementMock = $this->getMockForAbstractClass(CartManagementInterface::class);
        $this->quoteIdMaskFactoryMock = $this->createPartialMock(QuoteIdMaskFactory::class, ['create']);
        $this->model = $objectManager->getObject(
            GuestCartService::class,
            [
                'cartManagement' => $this->cartManagementMock,
                'quoteIdMaskFactory' => $this->quoteIdMaskFactoryMock
            ]
        );

        $quoteIdMaskMock = $this->createPartialMock(QuoteIdMask::class, ['load', 'getQuoteId']);
        $this->quoteIdMaskFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($quoteIdMaskMock);

        $quoteIdMaskMock->expects($this->once())
            ->method('load')
            ->with($this->cartIdMask, 'masked_id')
            ->willReturn($quoteIdMaskMock);
        $quoteIdMaskMock->expects($this->once())
            ->method('getQuoteId')
            ->willReturn($this->cartId);
    }

    /**
     * Test addPromoProducts method
     */
    public function testAddPromoProducts()
    {
        $ruleMetadataMock = $this->getMockForAbstractClass(RuleMetadataInterface::class);
        $metadataRules = [$ruleMetadataMock];

        $this->cartManagementMock->expects($this->once())
            ->method('addPromoProducts')
            ->with($this->cartId, $metadataRules);

        $this->model->addPromoProducts($this->cartIdMask, $metadataRules);
    }

    /**
     * Test removeUnusedPromoData method
     */
    public function testRemoveUnusedPromoData()
    {
        $this->cartManagementMock->expects($this->once())
            ->method('removeUnusedPromoData')
            ->with($this->cartId);

        $this->model->removeUnusedPromoData($this->cartIdMask);
    }
}
