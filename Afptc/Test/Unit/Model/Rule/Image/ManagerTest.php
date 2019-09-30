<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Image;

use Magento\Framework\Image\AdapterFactory;
use Aheadworks\Afptc\Model\Rule\Image\Manager\PathBuilder;
use Aheadworks\Afptc\Model\Rule\Image\Manager\UrlBuilder;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Model\Rule\Image\Manager;
use Magento\Framework\Image;

/**
 * Class ManagerTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Image
 */
class ManagerTest extends TestCase
{
    /**
     * @var Manager
     */
    private $model;

    /**
     * @var AdapterFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $imageFactoryMock;

    /**
     * @var PathBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $pathBuilderMock;

    /**
     * @var UrlBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $urlBuilderMock;

    /**
     * @var WriteInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mediaDirectoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->imageFactoryMock = $this->createPartialMock(AdapterFactory::class, ['create']);
        $this->pathBuilderMock = $this->createPartialMock(
            PathBuilder::class,
            ['getCacheFolderPath', 'cleanFile']
        );
        $this->urlBuilderMock = $this->createPartialMock(
            UrlBuilder::class,
            ['getUrlToMediaFolder', 'getFilePath']
        );
        $this->mediaDirectoryMock = $this->getMockForAbstractClass(WriteInterface::class);
        $this->model = $objectManager->getObject(
            Manager::class,
            [
                'pathBuilder' => $this->pathBuilderMock,
                'imageFactory' => $this->imageFactoryMock,
                'urlBuilder' => $this->urlBuilderMock,
                'mediaDirectory' => $this->mediaDirectoryMock
            ]
        );
    }

    /**
     * Test for resize method
     *
     * @dataProvider resizeProvider
     * @param bool $imageExists
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testResize($imageExists)
    {
        $image = 'test.png';
        $width = '100';
        $height = '100';
        $cacheFolderPath = 'cache/100x100';
        $absolutePath = '/var/www/mysite/pub/media/aw_afptc/images';
        $originalImageAbsPath = $absolutePath;
        $resizedImageAbsPath = $absolutePath . '/' . $cacheFolderPath;

        $this->pathBuilderMock->expects($this->once())
            ->method('getCacheFolderPath')
            ->with($width, $height)
            ->willReturn($cacheFolderPath);

        $this->mediaDirectoryMock->expects($this->exactly(2))
            ->method('getAbsolutePath')
            ->withConsecutive([PathBuilder::FILE_DIR_PART], [PathBuilder::FILE_DIR_PART . '/' . $cacheFolderPath])
            ->willReturnOnConsecutiveCalls($originalImageAbsPath, $resizedImageAbsPath);

        $this->mediaDirectoryMock->expects($this->once())
            ->method('isFile')
            ->with($resizedImageAbsPath . '/' . $image)
            ->willReturn($imageExists);

        if (!$imageExists) {
            $imageModel = $this->createPartialMock(
                Image::class,
                [
                    'open',
                    'constrainOnly',
                    'keepTransparency',
                    'keepFrame',
                    'keepAspectRatio',
                    'backgroundColor',
                    'resize',
                    'save'
                ]
            );

            $this->imageFactoryMock->expects($this->once())
                ->method('create')
                ->willReturn($imageModel);

            $imageModel->expects($this->once())
                ->method('open')
                ->with($originalImageAbsPath . '/' . $image)
                ->willReturnSelf();
            $imageModel->expects($this->once())
                ->method('constrainOnly')
                ->with(true)
                ->willReturnSelf();
            $imageModel->expects($this->once())
                ->method('keepTransparency')
                ->with(true)
                ->willReturnSelf();
            $imageModel->expects($this->once())
                ->method('keepFrame')
                ->with(true)
                ->willReturnSelf();
            $imageModel->expects($this->once())
                ->method('keepAspectRatio')
                ->with(true)
                ->willReturnSelf();
            $imageModel->expects($this->once())
                ->method('backgroundColor')
                ->with([255, 255, 255])
                ->willReturnSelf();
            $imageModel->expects($this->once())
                ->method('resize')
                ->with($width, $height)
                ->willReturnSelf();
            $imageModel->expects($this->once())
                ->method('save')
                ->with($resizedImageAbsPath . '/' . $image)
                ->willReturnSelf();
        }

        $this->urlBuilderMock->expects($this->once())
            ->method('getUrlToMediaFolder')
            ->willReturn($absolutePath);
        $this->pathBuilderMock->expects($this->once())
            ->method('cleanFile')
            ->willReturn($image);

        $this->assertSame(
            $absolutePath . PathBuilder::FILE_DIR_PART . '/' . $image,
            $this->model->resize($image, $width, $height)
        );
    }

    /**
     * Data provider for testResize method
     */
    public function resizeProvider()
    {
        return [
            'image exists' => [false],
            'image does not exist' => [true],
        ];
    }
}
