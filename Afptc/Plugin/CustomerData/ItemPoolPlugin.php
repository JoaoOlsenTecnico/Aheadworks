<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\CustomerData;

use Magento\Checkout\CustomerData\ItemPoolInterface;
use Magento\Quote\Model\Quote\Item;
use Aheadworks\Afptc\Model\Cart\Item\Renderer\PriceAdjustment;

/**
 * Class ItemPoolPlugin
 *
 * @package Aheadworks\Afptc\Plugin\CustomerData
 */
class ItemPoolPlugin
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
     * Get item data by quote item
     *
     * @param ItemPoolInterface $subject
     * @param Item $item
     * @return array
     */
    public function beforeGetItemData($subject, $item)
    {
        $this->priceAdjustment->adjustForRendering($item);
        return [$item];
    }
}
