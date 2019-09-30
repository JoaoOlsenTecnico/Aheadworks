<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Image\Manager;

/**
 * Class PathBuilder
 *
 * @package Aheadworks\Afptc\Model\Rule\Image\Manager
 */
class PathBuilder
{
    /**
     * @var string
     */
    const FILE_DIR_PART = 'aw_afptc/images';

    /**
     * Folder used for storing cached images
     */
    const CACHE_DIR_PART = 'cache';

    /**
     * Get file path
     *
     * @param string $fileName
     * @return string
     */
    public function getFilePath($fileName)
    {
        return self::FILE_DIR_PART . '/' . ltrim($fileName, '/');
    }

    /**
     * Get cache folder to store resized images
     *
     * @param int|null $width
     * @param int|null $height
     * @return string
     */
    public function getCacheFolderPath($width = null, $height = null)
    {
        $path = self::CACHE_DIR_PART;
        if ($width !== null) {
            $path .= '/' . $width . 'x';
            if ($height !== null) {
                $path .= $height ;
            }
        }

        return $path;
    }

    /**
     * Clean file path
     *
     * @param string $fileName
     * @return string
     */
    public function cleanFile($fileName)
    {
        return ltrim(str_replace('\\', '/', $fileName), '/');
    }
}
