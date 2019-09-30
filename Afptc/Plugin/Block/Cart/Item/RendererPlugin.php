<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Block\Cart\Item;

use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Checkout\Block\Cart\Item\Renderer;
use Aheadworks\Afptc\Model\Cart\Item\Renderer\PriceAdjustment;

/**
 * Class RendererPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Block\Cart\Item
 */
class RendererPlugin
{
    /**
     * @var PriceAdjustment
     */
    private $priceAdjustment;

    /**
     * @param PriceAdjustment $priceAdjustment
     */
    public function __construct(PriceAdjustment $priceAdjustment)
    {
        $this->priceAdjustment = $priceAdjustment;
    }

    /**
     * Get modified quote item for correct price rendering for promo products
     *
     * @param Renderer $subject
     * @param AbstractItem $quoteItem
     * @return array
     */
    public function beforeSetItem($subject, $quoteItem)
    {
        $this->priceAdjustment->adjustForRendering($quoteItem);
        return [$quoteItem];
    }
}
