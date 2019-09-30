<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor;

/**
 * Interface ProcessorInterface
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor
 */
interface ProcessorInterface
{
    /**
     * Prepare entity data for save
     *
     * @param array $data
     * @return array
     */
    public function process($data);
}
