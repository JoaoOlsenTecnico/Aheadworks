<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Block\Tax\Item;

use Magento\Sales\Model\Order\CreditMemo\Item as CreditMemoItem;
use Magento\Sales\Model\Order\Invoice\Item as InvoiceItem;
use Magento\Sales\Model\Order\Item as OrderItem;
use Magento\Tax\Block\Item\Price\Renderer;

/**
 * Class PriceRendererPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Block\Tax\Item
 */
class PriceRendererPlugin
{
    /**
     * Subtract Afptc data
     *
     * @param Renderer $subject
     * @param \Closure $proceed
     * @param OrderItem|InvoiceItem|CreditMemoItem $item
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetTotalAmount(
        Renderer $subject,
        \Closure $proceed,
        $item
    ) {
        $totalAmount = $proceed($item);
        // Convert to the same type
        return (string)(float)$totalAmount - (string)(float)$item->getAwAfptcAmount();
    }

    /**
     * Subtract Afptc data
     *
     * @param Renderer $subject
     * @param \Closure $proceed
     * @param OrderItem|InvoiceItem|CreditMemoItem $item
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetBaseTotalAmount(
        Renderer $subject,
        \Closure $proceed,
        $item
    ) {
        $totalAmount = $proceed($item);
        // Convert to the same type
        return (string)(float)$totalAmount - (string)(float)$item->getBaseAwAfptcAmount();
    }
}
