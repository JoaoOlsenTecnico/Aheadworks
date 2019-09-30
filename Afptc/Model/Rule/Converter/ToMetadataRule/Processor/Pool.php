<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor;

/**
 * Class Pool
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor
 */
class Pool
{
    /**#@+
     * Processor types
     */
    const POPUP_PROCESSOR = 'popup';
    const AUTO_ADD_PROCESSOR = 'autoAdd';
    const DISCOUNT = 'discount';
    /**#@-*/

    /**
     * @var array
     */
    private $processors = [];

    /**
     * @param array $processors
     */
    public function __construct($processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * Retrieves metadata for engine code
     *
     * @param string $type
     * @return ProcessorInterface
     * @throws \Exception
     */
    public function getProcessor($type)
    {
        if (!isset($this->processors[$type])) {
            throw new \Exception(sprintf('Unknown processor: %s requested', $type));
        }
        return $this->processors[$type];
    }
}
