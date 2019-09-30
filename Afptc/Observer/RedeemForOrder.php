<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Class RedeemForOrder
 *
 * @package Aheadworks\Afptc\Observer
 */
class RedeemForOrder implements ObserverInterface
{
    /**
     *  {@inheritDoc}
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        /** @var $order \Magento\Sales\Model\Order **/
        $order = $event->getOrder();
        /** @var $quote \Magento\Quote\Model\Quote $quote */
        $quote = $event->getQuote();

        if ($quote->getAwAfptcAmount()) {
            $order->setAwAfptcAmount($quote->getAwAfptcAmount());
            $order->setBaseAwAfptcAmount($quote->getBaseAwAfptcAmount());
            $order->setAwAfptcUsesCoupon($quote->getAwAfptcUsesCoupon());
        }
    }
}
