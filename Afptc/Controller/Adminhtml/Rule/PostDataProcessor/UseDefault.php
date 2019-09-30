<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor;

/**
 * Class UseDefault
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor
 */
class UseDefault implements ProcessorInterface
{
    /**
     * Use default data
     */
    const USE_DEFAULT_DATA = 'use_default';

    /**
     * Reset data to default for fields where it is required
     *
     * @param array $data
     * @return array
     */
    public function process($data)
    {
        if (isset($data[self::USE_DEFAULT_DATA]) && is_array($data[self::USE_DEFAULT_DATA])) {
            foreach ($data[self::USE_DEFAULT_DATA] as $field => $isTicked) {
                if ($isTicked) {
                    $data[$field] = null;
                }
            }
        }

        return $data;
    }
}
