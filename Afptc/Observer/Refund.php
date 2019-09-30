<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Class Refund
 *
 * @package Aheadworks\Afptc\Observer
 */
class Refund implements ObserverInterface
{
    /**
     * Set refund amount to creditmemo
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $order = $creditmemo->getOrder();

        // If refund Afptc amount
        if ($creditmemo->getBaseAwAfptcAmount()) {
            $order->setBaseAwAfptcRefunded($order->getBaseAwAfptcRefunded() + $creditmemo->getBaseAwAfptcAmount());
            $order->setAwAfptcRefunded($order->getAwAfptcRefunded() + $creditmemo->getAwAfptcAmount());
        }

        return $this;
    }
}
