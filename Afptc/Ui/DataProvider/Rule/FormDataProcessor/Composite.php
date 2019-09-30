<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor;

/**
 * Class Composite
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor
 */
class Composite implements ProcessorInterface
{
    /**
     * @var array[]
     */
    private $processors;

    /**
     * @param array $processors
     */
    public function __construct(array $processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareEntityData($data)
    {
        foreach ($this->processors as $processor) {
            $data = $processor->prepareEntityData($data);
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareMetaData($data)
    {
        foreach ($this->processors as $processor) {
            $data = $processor->prepareMetaData($data);
        }
        return $data;
    }
}
