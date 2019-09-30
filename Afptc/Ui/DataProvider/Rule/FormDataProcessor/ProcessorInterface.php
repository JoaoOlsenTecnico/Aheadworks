<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor;

/**
 * Interface ProcessorInterface
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor
 */
interface ProcessorInterface
{
    /**
     * Prepare entity data for form
     *
     * @param array $data
     * @return array
     */
    public function prepareEntityData($data);

    /**
     * Prepare meta data for form
     *
     * @param array $meta
     * @return array
     */
    public function prepareMetaData($meta);
}
