<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Cart\Item\ExtensionAttributes\Processor;

/**
 * Class Composite
 *
 * @package Aheadworks\Afptc\Model\Cart\Item\ExtensionAttributes\Processor
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
    public function prepareDataBeforeSave($item)
    {
        foreach ($this->processors as $processor) {
            $processor->prepareDataBeforeSave($item);
        }
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataAfterLoad($item)
    {
        foreach ($this->processors as $processor) {
            $processor->prepareDataAfterLoad($item);
        }
        return $item;
    }
}
