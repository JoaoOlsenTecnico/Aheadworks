<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Cart\Promo\Item;

use Aheadworks\Afptc\Model\Cart\Promo\Item\QtyLabelResolver;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Info
 *
 * @package Aheadworks\Afptc\Block\Cart\Promo\Item
 */
class Info extends Template
{
    /**
     * @var QtyLabelResolver
     */
    private $qtyLabelResolver;

    /**
     * @param Context $context
     * @param QtyLabelResolver $qtyLabelResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        QtyLabelResolver $qtyLabelResolver,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->qtyLabelResolver = $qtyLabelResolver;
    }

    /**
     * Retrieve qty label
     *
     * @return string
     */
    public function getQtyLabel()
    {
        return $this->qtyLabelResolver->resolve($this->getItem());
    }
}
