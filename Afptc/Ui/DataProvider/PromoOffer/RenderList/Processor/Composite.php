<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor;

/**
 * Class Composite
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor
 */
class Composite implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * @param ProcessorInterface[] $processors
     */
    public function __construct(array $processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRender($promoOfferRender, $metadataRules)
    {
        foreach ($this->processors as $processor) {
            $processor->prepareRender($promoOfferRender, $metadataRules);
        }
    }
}
