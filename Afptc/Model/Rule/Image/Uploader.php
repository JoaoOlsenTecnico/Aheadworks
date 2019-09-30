<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Image;

use Magento\MediaStorage\Model\File\UploaderFactory;
use Aheadworks\Afptc\Model\Rule\Image\Manager\PathBuilder;

/**
 * Class Uploader
 *
 * @package Aheadworks\Afptc\Model\Rule\Image
 */
class Uploader
{
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var Manager
     */
    private $imageManager;

    /**
     * @param UploaderFactory $uploaderFactory
     * @param Manager $imageManager
     */
    public function __construct(
        UploaderFactory $uploaderFactory,
        Manager $imageManager
    ) {
        $this->uploaderFactory = $uploaderFactory;
        $this->imageManager = $imageManager;
    }

    /**
     * Upload image to media directory
     *
     * @param string $fileId
     * @return array
     * @throws \Exception
     */
    public function uploadToMediaFolder($fileId)
    {
        $result = ['file' => '', 'size' => '', 'type' => ''];
        $mediaDirectory = $this->imageManager->getAbsolutePath(PathBuilder::FILE_DIR_PART);

        /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader
            ->setAllowRenameFiles(true)
            ->setFilesDispersion(false)
            ->setAllowedExtensions($this->getAllowedExtensions());

        $result = array_intersect_key($uploader->save($mediaDirectory), $result);
        $result['url'] = $this->imageManager->getUrl($result['file']);

        return $result;
    }

    /**
     * Get allowed file extensions
     *
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'gif', 'png'];
    }
}
