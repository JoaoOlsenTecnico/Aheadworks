<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Controller\Product;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Controller\Product\AddToCart;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\Quote;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Controller\Product\AddToCart\PostDataProcessor;
use Magento\Framework\Controller\Result\JsonFactory;
use Aheadworks\Afptc\Api\CartManagementInterface;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;

/**
 * Class AddToCartTest
 * @package Aheadworks\Afptc\Test\Unit\Controller\Product
 */
class AddToCartTest extends TestCase
{
    /**
     * @var AddToCart
     */
    private $controller;

    /**
     * @var JsonFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultJsonFactoryMock;

    /**
     * @var CartManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartManagementMock;

    /**
     * @var RuleManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleManagementMock;

    /**
     * @var CheckoutSession|\PHPUnit_Framework_MockObject_MockObject
     */
    private $checkoutSessionMock;

    /**
     * @var PostDataProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $postDataProcessorMock;

    /**
     * @var ManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $messageManagerMock;

    /**
     * @var Http|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var RedirectFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultRedirectFactoryMock;

    /**
     * @var int
     */
    private $quoteId = 1;

    /**
     * @var RuleMetadataInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $metadataRulesMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->resultJsonFactoryMock = $this->createPartialMock(JsonFactory::class, ['create']);
        $this->cartManagementMock = $this->getMockForAbstractClass(CartManagementInterface::class);
        $this->checkoutSessionMock = $this->createPartialMock(CheckoutSession::class, ['getQuote']);
        $this->ruleManagementMock = $this->getMockForAbstractClass(RuleManagementInterface::class);
        $this->postDataProcessorMock = $this->createPartialMock(
            PostDataProcessor::class,
            ['prepareRequestItems', 'prepareMetadataRulesByItems']
        );
        $this->messageManagerMock = $this->getMockForAbstractClass(ManagerInterface::class);
        $this->requestMock = $this->createMock(Http::class);

        $this->resultRedirectFactoryMock = $this->getMockBuilder(RedirectFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $context = $objectManager->getObject(
            Context::class,
            [
                'messageManager' => $this->messageManagerMock,
                'request' => $this->requestMock,
                'resultRedirectFactory' => $this->resultRedirectFactoryMock
            ]
        );

        $this->controller = $objectManager->getObject(
            AddToCart::class,
            [
                'context' => $context,
                'resultJsonFactory' => $this->resultJsonFactoryMock,
                'cartManagement' => $this->cartManagementMock,
                'checkoutSession' => $this->checkoutSessionMock,
                'ruleManagement' => $this->ruleManagementMock,
                'postDataProcessor' => $this->postDataProcessorMock
            ]
        );
    }

    /**
     * Test execute method
     */
    public function testExecute()
    {
        $this->initExecute();
        $this->requestMock->expects($this->once())
            ->method('isAjax')
            ->willReturn(true);

        $this->controller->execute();
    }

    /**
     * Test execute method on none ajax request
     */
    public function testExecuteOnNonAjaxRequest()
    {
        $this->initExecute();
        $this->requestMock->expects($this->once())
            ->method('isAjax')
            ->willReturn(false);
        $resultRedirectMock = $this->createMock(Redirect::class);
        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultRedirectMock);
        $resultRedirectMock->expects($this->any())
            ->method('setRefererUrl')
            ->willReturnSelf();

        $this->cartManagementMock->expects($this->once())
            ->method('addPromoProducts')
            ->with($this->quoteId, $this->metadataRulesMock);
        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('You added promo product(s) to your shopping cart.'));

        $this->controller->execute();
    }

    /**
     * Test execute method on localized exception
     */
    public function testExecuteOnLocalizedException()
    {
        $exceptionMsg = __('Exception');
        $exception = new LocalizedException($exceptionMsg);

        $this->initExecute();
        $this->requestMock->expects($this->once())
            ->method('isAjax')
            ->willReturn(true);
        $this->cartManagementMock->expects($this->once())
            ->method('addPromoProducts')
            ->with($this->quoteId, $this->metadataRulesMock)
            ->willThrowException($exception);

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with($exceptionMsg);

        $this->controller->execute();
    }

    /**
     * Test execute method on exception
     */
    public function testExecuteOnException()
    {
        $exception = new \Exception('Exception');

        $this->initExecute();
        $this->requestMock->expects($this->once())
            ->method('isAjax')
            ->willReturn(true);
        $this->cartManagementMock->expects($this->once())
            ->method('addPromoProducts')
            ->with($this->quoteId, $this->metadataRulesMock)
            ->willThrowException($exception);

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('We can\'t add this promo product(s) to your shopping cart right now.'));

        $this->controller->execute();
    }

    /**
     * Initialize execute method
     *
     * @return void
     */
    private function initExecute()
    {
        $this->quoteId = 1;
        $items = [];
        $preparedItems = [];
        $quoteMock = $this->createPartialMock(Quote::class, ['getStore', 'getId']);
        $this->metadataRulesMock = [$this->getMockForAbstractClass(RuleMetadataInterface::class)];
        $resultJsonMock = $this->createPartialMock(Json::class, ['setData']);

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('items', [])
            ->willReturn($items);
        $this->checkoutSessionMock->expects($this->once())
            ->method('getQuote')
            ->willReturn($quoteMock);

        $quoteMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn($this->quoteId);

        $this->ruleManagementMock->expects($this->once())
            ->method('getPopUpMetadataRules')
            ->with($this->quoteId)
            ->willReturn($this->metadataRulesMock);

        $this->postDataProcessorMock->expects($this->once())
            ->method('prepareRequestItems')
            ->with($items)
            ->willReturn($preparedItems);
        $this->postDataProcessorMock->expects($this->once())
            ->method('prepareMetadataRulesByItems')
            ->with($this->metadataRulesMock, $preparedItems)
            ->willReturn($this->metadataRulesMock);

        $this->resultJsonFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($resultJsonMock);
        $resultJsonMock->expects($this->any())
            ->method('setData')
            ->with([])
            ->willReturnSelf();
    }
}
