<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Total\Invoice;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

/**
 * Class Afptc
 *
 * @package Aheadworks\Afptc\Model\Total\Invoice
 */
class Afptc extends AbstractTotal
{
    /**
     * {@inheritDoc}
     */
    public function collect(Invoice $invoice)
    {
        $invoice->setAwAfptcAmount(0);
        $invoice->setBaseAwAfptcAmount(0);

        $order = $invoice->getOrder();
        if ($order->getBaseAwAfptcAmount()
            && $order->getBaseAwAfptcInvoiced() != $order->getBaseAwAfptcAmount()
        ) {
            $totalAmount = 0;
            $baseTotalAmount = 0;

            /** @var $item \Magento\Sales\Model\Order\Invoice\Item */
            foreach ($invoice->getAllItems() as $item) {
                $orderItem = $item->getOrderItem();
                if ($orderItem->isDummy()) {
                    continue;
                }

                $orderItemAmount = (double)$orderItem->getAwAfptcAmount();
                $baseOrderItemAmount = (double)$orderItem->getBaseAwAfptcAmount();
                $orderItemQty = $orderItem->getAwAfptcQty();

                if ($orderItemAmount && $orderItemQty) {
                    // Resolve rounding problems
                    $amount = $orderItemAmount - $orderItem->getAwAfptcInvoiced();
                    $baseAmount = $baseOrderItemAmount - $orderItem->getBaseAwAfptcInvoiced();
                    $activeQty = $orderItemQty - $orderItem->getAwAfptcQtyInvoiced();
                    $qtyToInvoice = $item->getQty() > $activeQty ? $activeQty : $item->getQty();

                    if (!$item->isLast()) {
                        $amount = $invoice->roundPrice(
                            $amount / $activeQty * $qtyToInvoice,
                            'regular',
                            true
                        );
                        $baseAmount = $invoice->roundPrice(
                            $baseAmount / $activeQty * $qtyToInvoice,
                            'base',
                            true
                        );
                    }

                    $item->setAwAfptcAmount($amount);
                    $item->setBaseAwAfptcAmount($baseAmount);

                    $orderItem->setAwAfptcInvoiced(
                        $orderItem->getAwAfptcInvoiced() + $item->getAwAfptcAmount()
                    );
                    $orderItem->setBaseAwAfptcInvoiced(
                        $orderItem->getBaseAwAfptcInvoiced() + $item->getBaseAwAfptcAmount()
                    );
                    $orderItem->setAwAfptcQtyInvoiced(
                        $orderItem->getAwAfptcQtyInvoiced() + $qtyToInvoice
                    );

                    $totalAmount += $amount;
                    $baseTotalAmount += $baseAmount;
                }
            }

            if ($baseTotalAmount > 0) {
                $invoice->setBaseAwAfptcAmount(-$baseTotalAmount);
                $invoice->setAwAfptcAmount(-$totalAmount);
            }

            $invoice->setGrandTotal($invoice->getGrandTotal() - $totalAmount);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $baseTotalAmount);
        }
        return $this;
    }
}
