<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\Quote;

use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Sales\Model\Order\Item;

/**
 * Class ConvertQuoteItemToOrderItemPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Model\Quote
 */
class ConvertQuoteItemToOrderItemPlugin
{
    /**
     * @param ToOrderItem $subject
     * @param \Closure $proceed
     * @param AbstractItem $item
     * @param array $additional
     * @return Item
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundConvert(
        ToOrderItem $subject,
        \Closure $proceed,
        AbstractItem $item,
        $additional = []
    ) {
        /** @var $orderItem \Magento\Sales\Model\Order\Item */
        $orderItem = $proceed($item, $additional);

        $orderItem->setAwAfptcPercent($item->getAwAfptcPercent());
        $orderItem->setAwAfptcQty($item->getAwAfptcQty());
        $orderItem->setAwAfptcIsPromo($item->getAwAfptcIsPromo());
        $orderItem->setAwAfptcRuleIds($item->getAwAfptcRuleIds());
        $orderItem->setAwAfptcAmount($item->getAwAfptcAmount());
        $orderItem->setBaseAwAfptcAmount($item->getBaseAwAfptcAmount());

        return $orderItem;
    }
}
