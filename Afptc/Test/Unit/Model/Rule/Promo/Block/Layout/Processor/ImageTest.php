<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Promo\Block\Layout\Processor;

use Aheadworks\Afptc\Api\Data\PromoInterface;
use Aheadworks\Afptc\Model\Rule\Image\Manager as ImageManager;
use Magento\Framework\Stdlib\ArrayManager;
use Aheadworks\Afptc\Model\Theme\View\Config as ViewConfig;
use Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor\Image;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Api\Data\PromoInfoBlockInterface;
use Aheadworks\Afptc\Model\Source\Rule\Promo\Renderer\Placement;

/**
 * Class ImageTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Promo\Block\Layout\Processor
 */
class ImageTest extends TestCase
{
    /**
     * @var Image
     */
    private $model;

    /**
     * @var ArrayManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $arrayManagerMock;

    /**
     * @var ImageManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $imageManagerMock;

    /**
     * @var ViewConfig|\PHPUnit_Framework_MockObject_MockObject
     */
    private $viewConfigMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->arrayManagerMock = $this->createPartialMock(ArrayManager::class, ['merge']);
        $this->imageManagerMock = $this->createPartialMock(ImageManager::class, ['resize']);
        $this->viewConfigMock = $this->createPartialMock(ViewConfig::class, ['getImageAttributesByPlacement']);
        $this->model = $objectManager->getObject(
            Image::class,
            [
                'arrayManager' => $this->arrayManagerMock,
                'imageManager' => $this->imageManagerMock,
                'viewConfig' => $this->viewConfigMock
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
        $promo->expects($this->exactly(2))
            ->method('getPromoImageAltText')
            ->willReturn('altText');
        $this->arrayManagerMock->expects($this->once())
            ->method('merge');
        $image = 'test.png';
        $promo->expects($this->exactly(2))
            ->method('getPromoImage')
            ->willReturn($image);
        $imageAttributes = [
            ViewConfig::IMAGE_WIDTH => '200',
            ViewConfig::IMAGE_HEIGHT => '200'
        ];
        $this->viewConfigMock->expects($this->exactly(2))
            ->method('getImageAttributesByPlacement')
            ->withConsecutive(
                [ViewConfig::PRODUCT_IMAGE, Placement::PRODUCT_PAGE],
                [ViewConfig::POPUP_IMAGE, Placement::PRODUCT_PAGE]
            )->willReturn($imageAttributes);
        $this->imageManagerMock->expects($this->exactly(2))
            ->method('resize')
            ->with(
                $image,
                $imageAttributes[ViewConfig::IMAGE_WIDTH],
                $imageAttributes[ViewConfig::IMAGE_HEIGHT]
            )->willReturn('some_url');

        $this->model->process([], $promoInfoBlockMock, Placement::PRODUCT_PAGE, 'scope1');
    }
}
