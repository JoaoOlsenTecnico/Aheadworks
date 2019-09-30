<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\ResourceModel;

use Aheadworks\Afptc\Model\Cart\Item\ExtensionAttributes\Processor\Composite;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\ResourceModel\Quote as ResourceQuote;

/**
 * Class QuotePlugin
 *
 * @package Aheadworks\Afptc\Plugin\Model\ResourceModel
 */
class QuotePlugin
{
    /**
     * @var Composite
     */
    private $processor;

    /**
     * @param Composite $processor
     */
    public function __construct(
        Composite $processor
    ) {
        $this->processor = $processor;
    }

    /**
     * Convert quote items data after load quote items
     *
     * @param ResourceQuote $subject
     * @param Quote $quote
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave($subject, $quote)
    {
        foreach ($quote->getAllItems() as $item) {
            $this->processor->prepareDataBeforeSave($item);
        }
    }
}
