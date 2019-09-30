<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Image\Manager;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Model\Rule\Image\Manager\UrlBuilder;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class UrlBuilderTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Image\Manager
 */
class UrlBuilderTest extends TestCase
{
    /**
     * @var UrlBuilder
     */
    private $model;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagerMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->model = $objectManager->getObject(
            UrlBuilder::class,
            [
                'storeManager' => $this->storeManagerMock
            ]
        );
    }

    /**
     * Test for getUrlToMediaFolder method
     */
    public function testGetUrlToMediaFolder()
    {
        $baseUrl = 'www.store.com';
        $storeMock = $this->createPartialMock(Store::class, ['getBaseUrl']);
        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);
        $storeMock->expects($this->once())
            ->method('getBaseUrl')
            ->with(UrlInterface::URL_TYPE_MEDIA)
            ->willReturn($baseUrl);
        $this->assertSame($baseUrl, $this->model->getUrlToMediaFolder());
    }

    /**
     * Test for getUrlToMediaFolder method on exception
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testGetUrlToMediaFolderOnException()
    {
        $exception = new NoSuchEntityException(__('Exception message.'));
        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willThrowException($exception);
        $this->model->getUrlToMediaFolder();
    }
}
