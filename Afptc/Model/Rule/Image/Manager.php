<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Image;

use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Image\Factory;
use Aheadworks\Afptc\Model\Rule\Image\Manager\PathBuilder;
use Aheadworks\Afptc\Model\Rule\Image\Manager\UrlBuilder;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\WriteInterface;

/**
 * Class Manager
 *
 * @package Aheadworks\Afptc\Model\Rule\Image
 */
class Manager
{
    /**
     * @var Factory
     */
    protected $imageFactory;

    /**
     * @var PathBuilder
     */
    private $pathBuilder;

    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * @var WriteInterface
     */
    private $mediaDirectory;

    /**
     * @param Filesystem $filesystem
     * @param AdapterFactory $imageFactory
     * @param PathBuilder $pathBuilder
     * @param UrlBuilder $urlBuilder
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Filesystem $filesystem,
        AdapterFactory $imageFactory,
        PathBuilder $pathBuilder,
        UrlBuilder $urlBuilder
    ) {
        $this->imageFactory = $imageFactory;
        $this->pathBuilder = $pathBuilder;
        $this->urlBuilder = $urlBuilder;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * Resize image
     *
     * @param string $image
     * @param int|null $width
     * @param int|null $height
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resize($image, $width = null, $height = null)
    {
        $cacheFolderPath = $this->pathBuilder->getCacheFolderPath($width, $height);
        $originalImageAbsPath = $this->getAbsolutePath(PathBuilder::FILE_DIR_PART) . '/' .  $image;
        $resizedImageAbsPath = $this->getAbsolutePath(PathBuilder::FILE_DIR_PART .
                '/' . $cacheFolderPath) . '/' . $image;

        if (!$this->fileExists($resizedImageAbsPath)) {
            $imageFactory = $this->imageFactory->create();
            $imageFactory->open($originalImageAbsPath);
            $imageFactory->constrainOnly(true);
            $imageFactory->keepTransparency(true);
            $imageFactory->keepFrame(true);
            $imageFactory->keepAspectRatio(true);
            $imageFactory->backgroundColor([255, 255, 255]);
            $imageFactory->resize($width, $height);
            $imageFactory->save($resizedImageAbsPath);
        }

        return $this->getUrl($cacheFolderPath . '/' . $image);
    }

    /**
     * Get file url
     *
     * @param string $file
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrl($file)
    {
        $urlToMediaFolder = $this->urlBuilder->getUrlToMediaFolder();
        $file = $this->pathBuilder->cleanFile($file);

        return $urlToMediaFolder . PathBuilder::FILE_DIR_PART . '/' . $file;
    }

    /**
     * Get absolute path to image
     *
     * @param string $path
     * @return string
     */
    public function getAbsolutePath($path)
    {
        return $this->mediaDirectory->getAbsolutePath($path);
    }

    /**
     * Get file statistics data
     *
     * @param string $fileName
     * @return array
     */
    public function getStat($fileName)
    {
        return $this->mediaDirectory->stat($this->pathBuilder->getFilePath($fileName));
    }

    /**
     * Check if file already exists
     *
     * @param string $file
     * @return bool
     */
    public function fileExists($file)
    {
        return $this->mediaDirectory->isFile($file);
    }
}
