<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Class IncreaseOrderAfptcInvoicedAmount
 *
 * @package Aheadworks\Afptc\Observer
 */
class IncreaseOrderAfptcInvoicedAmount implements ObserverInterface
{
    /**
     * Increase order aw_afptc_invoiced attribute based on created invoice
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
        if ($invoice->getBaseAwAfptcAmount()) {
            $order->setBaseAwAfptcInvoiced(
                $order->getBaseAwAfptcInvoiced() + $invoice->getBaseAwAfptcAmount()
            );
            $order->setAwAfptcInvoiced(
                $order->getAwAfptcInvoiced() + $invoice->getAwAfptcAmount()
            );
        }
        return $this;
    }
}
