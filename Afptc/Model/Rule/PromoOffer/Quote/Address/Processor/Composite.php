<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor;

/**
 * Class Composite
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address
 */
class Composite implements ProcessorInterface
{
    /**
     * @var array
     */
    private $processors;

    /**
     * @param array $processors
     */
    public function __construct(
        array $processors = []
    ) {
        $this->processors = $processors;
    }

    /**
     * {@inheritdoc}
     */
    public function process($address, $data = [])
    {
        foreach ($this->processors as $processor) {
            $data = $processor->process($address, $data);
        }

        return $data;
    }
}
