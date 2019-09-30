<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Image\Manager;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Model\Rule\Image\Manager\PathBuilder;

/**
 * Class PathBuilderTest
 *
 * @package Aheadworks\Afptc\Model\Rule\Image\Manager
 */
class PathBuilderTest extends TestCase
{
    /**
     * @var PathBuilder
     */
    private $model;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(PathBuilder::class, []);
    }

    /**
     * Test for getFilePath method
     */
    public function testGetPath()
    {
        $file = '/test.png';
        $result = PathBuilder::FILE_DIR_PART . $file;
        $this->assertSame($result, $this->model->getFilePath($file));
    }

    /**
     * Test for getCacheFolderPath method
     *
     * @dataProvider getCacheFolderPathDataProvider
     * @param int|null $width
     * @param int|null $height
     * @param string $result
     */
    public function testGetCacheFolderPath($width, $height, $result)
    {
        $this->assertSame($result, $this->model->getCacheFolderPath($width, $height));
    }

    /**
     * Data provider for testGetCacheFolderPath method
     */
    public function getCacheFolderPathDataProvider()
    {
        return [
            [null, null, PathBuilder::CACHE_DIR_PART],
            [null, 50, PathBuilder::CACHE_DIR_PART],
            [50, null, PathBuilder::CACHE_DIR_PART . '/50x'],
            [50, 50, PathBuilder::CACHE_DIR_PART . '/50x50']
        ];
    }

    /**
     * Test for cleanFile method
     */
    public function testCleanFile()
    {
        $file = '/test.png';
        $result = 'test.png';
        $this->assertSame($result, $this->model->cleanFile($file));
    }
}
