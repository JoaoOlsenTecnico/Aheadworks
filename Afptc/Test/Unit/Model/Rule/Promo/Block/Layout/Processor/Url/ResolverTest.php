<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Promo\Block\Layout\Processor\Url;

use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor\Url\Resolver;

/**
 * Class ResolverTest
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor\Url
 */
class ResolverTest extends TestCase
{
    /**
     * @var Resolver
     */
    private $model;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->urlBuilder = $this->getMockForAbstractClass(UrlInterface::class);
        $this->model = $objectManager->getObject(
            Resolver::class,
            [
                'urlBuilder' => $this->urlBuilder
            ]
        );
    }

    /**
     * Test resolve method
     */
    public function testResolve()
    {
        $host = 'www.example.com/';
        $relatedUrl = '/test.html';
        $result = $host . ltrim($relatedUrl, '/');

        $this->urlBuilder->expects($this->once())
            ->method('getUrl')
            ->with(ltrim($relatedUrl, '/'))
            ->willReturn($result);

        $this->assertSame($result, $this->model->resolve($relatedUrl));
    }

    /**
     * Test resolve method in case url is empty
     */
    public function testResolveOnEmptyUrl()
    {
        $this->assertSame(null, $this->model->resolve(''));
    }
}
