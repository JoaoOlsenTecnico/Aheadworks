<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor;

use Aheadworks\Afptc\Api\Data\PromoInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor\Url\Resolver as UrlResolver;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Api\Data\PromoInfoBlockInterface;
use Aheadworks\Afptc\Model\Source\Rule\Promo\Renderer\Placement;

/**
 * Class Url
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor
 */
class UrlTest extends TestCase
{
    /**
     * @var Url
     */
    private $model;

    /**
     * @var ArrayManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $arrayManagerMock;

    /**
     * @var UrlResolver|\PHPUnit_Framework_MockObject_MockObject
     */
    private $urlResolverMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->arrayManagerMock = $this->createPartialMock(ArrayManager::class, ['merge']);
        $this->urlResolverMock = $this->createPartialMock(UrlResolver::class, ['resolve']);
        $this->model = $objectManager->getObject(
            Url::class,
            [
                'arrayManager' => $this->arrayManagerMock,
                'urlResolver' => $this->urlResolverMock,
            ]
        );
    }

    /**
     * Test for process method
     */
    public function testProcess()
    {
        $promoInfoBlockMock = $this->getMockForAbstractClass(PromoInfoBlockInterface::class);
        $promo = $this->getMockForAbstractClass(PromoInterface::class);
        $promoInfoBlockMock->expects($this->once())
            ->method('getPromo')
            ->willReturn($promo);
        $this->arrayManagerMock->expects($this->once())
            ->method('merge');
        $this->urlResolverMock->expects($this->once())
            ->method('resolve')
            ->willReturn($promo);
        $url = 'some_url';
        $promo->expects($this->once())
            ->method('getPromoUrl')
            ->willReturn($url);
        $this->urlResolverMock->expects($this->once())
            ->method('resolve')
            ->with($url)
            ->willReturn('resolved url');
        $promo->expects($this->once())
            ->method('getPromoUrlText')
            ->willReturn('url text');

        $this->model->process([], $promoInfoBlockMock, Placement::PRODUCT_PAGE, 'scope1');
    }
}
