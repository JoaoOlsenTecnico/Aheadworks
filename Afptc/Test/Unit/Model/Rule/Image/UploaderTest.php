<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Image;

use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Model\Rule\Image\Uploader;
use Aheadworks\Afptc\Model\Rule\Image\Manager;
use Aheadworks\Afptc\Model\Rule\Image\Manager\PathBuilder;
use Magento\MediaStorage\Model\File\Uploader as MediaStorageUploader;
use Magento\Framework\Exception\FileSystemException;

/**
 * Class UploaderTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Image
 */
class UploaderTest extends TestCase
{
    /**
     * @var Uploader
     */
    private $model;
    
    /**
     * @var UploaderFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $uploaderFactoryMock;

    /**
     * @var Manager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $imageManagerMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->uploaderFactoryMock = $this->createPartialMock(UploaderFactory::class, ['create']);
        $this->imageManagerMock = $this->createPartialMock(
            Manager::class,
            ['getAbsolutePath', 'getUrl']
        );
        $this->model = $objectManager->getObject(
            Uploader::class,
            [
                'uploaderFactory' => $this->uploaderFactoryMock,
                'imageManager' => $this->imageManagerMock
            ]
        );
    }
    /**
     * Test uploadToMediaFolder method
     */
    public function testUploadToMediaFolder()
    {
        $fileType = 'image';
        $fileName = 'file.jpg';
        $fileSize = '123';
        $fileId = 'img';
        $mediaUrl = 'https://ecommerce.aheadworks.com/pub/media/aw_afptc/images' . $fileName;
        $mediaDirectory = '/var/www/mysite/pub/media/aw_afptc/images';
        $expected = array_merge(
            [
                'file' => $fileName,
                'size' => $fileSize,
                'type' => $fileType,
                'url' => $mediaUrl
            ]
        );

        $this->imageManagerMock->expects($this->once())
            ->method('getAbsolutePath')
            ->with(PathBuilder::FILE_DIR_PART)
            ->willReturn($mediaDirectory);

        $uploaderMock = $this->createPartialMock(
            MediaStorageUploader::class,
            ['setAllowRenameFiles', 'setFilesDispersion', 'setAllowedExtensions', 'save']
        );
        $this->uploaderFactoryMock->expects($this->once())
            ->method('create')
            ->with(['fileId' => $fileId])
            ->willReturn($uploaderMock);
        $uploaderMock->expects($this->once())
            ->method('setAllowRenameFiles')
            ->with(true)
            ->willReturnSelf();
        $uploaderMock->expects($this->once())
            ->method('setFilesDispersion')
            ->with(false)
            ->willReturnSelf();
        $uploaderMock->expects($this->once())
            ->method('setAllowedExtensions')
            ->with($this->model->getAllowedExtensions())
            ->willReturnSelf();

        $uploaderMock->expects($this->any())
            ->method('save')
            ->with($mediaDirectory)
            ->willReturn([
                'file' => $fileName,
                'size' => $fileSize,
                'type' => $fileType
            ]);
        $this->imageManagerMock->expects($this->any())
            ->method('getUrl')
            ->with($fileName)
            ->willReturn($mediaUrl);

        $this->assertEquals($expected, $this->model->uploadToMediaFolder($fileId));
    }

    /**
     * Test getStat method
     *
     * @expectedException \Magento\Framework\Exception\FileSystemException
     */
    public function testUploadToMediaFolderOnException()
    {
        $fileId = 'img';
        $exception = new FileSystemException(__('Exception message.'));

        $this->imageManagerMock->expects($this->once())
            ->method('getAbsolutePath')
            ->willThrowException($exception);

        $this->model->uploadToMediaFolder($fileId);
    }

    /**
     * Testing of getAllowedExtensions method
     */
    public function testGetAllowedExtensions()
    {
        $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];

        $this->assertEquals($allowedExtensions, $this->model->getAllowedExtensions());
    }
}
