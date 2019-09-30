<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Block\Adminhtml\Order\Create;

use Magento\Backend\Block\Widget\Button as WidgetButton;
use Magento\Quote\Model\Quote\Item;

/**
 * Class ConfigureButtonPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Block\Adminhtml\Order\Create
 */
class ConfigureButtonPlugin
{
    /**
     * Disable configure button for promo item
     *
     * @param $subject
     * @param \Closure $proceed
     * @param Item $quoteItem
     * @return string
     */
    public function aroundGetConfigureButtonHtml($subject, \Closure $proceed, $quoteItem)
    {
        if ($quoteItem && $quoteItem->getAwAfptcIsPromo()) {
            $product = $quoteItem->getProduct();

            $options = ['label' => __('Configure')];
            if ($product->canConfigure()) {
                $options['class'] = ' disabled promo';
                $options['onclick'] = sprintf('order.showQuoteItemConfiguration(%s)', $quoteItem->getId());
            } else {
                $options['class'] = ' disabled';
                $options['title'] = __('This product does not have any configurable options');
            }

            return $subject->getLayout()
                ->createBlock(WidgetButton::class)
                ->setData($options)
                ->toHtml();
        }

        return $proceed($quoteItem);
    }
}
