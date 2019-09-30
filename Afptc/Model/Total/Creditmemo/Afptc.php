<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Total\Creditmemo;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

/**
 * Class Afptc
 *
 * @package Aheadworks\Afptc\Model\Total\Creditmemo
 */
class Afptc extends AbstractTotal
{
    /**
     * {@inheritdoc}
     */
    public function collect(Creditmemo $creditmemo)
    {
        $creditmemo->setAwAfptcAmount(0);
        $creditmemo->setBaseAwAfptcAmount(0);

        $order = $creditmemo->getOrder();
        if ($order->getBaseAwAfptcAmount() && $order->getBaseAwAfptcInvoiced() != 0) {
            $totalAmount = 0;
            $baseTotalAmount = 0;

            /** @var $item \Magento\Sales\Model\Order\Creditmemo\Item */
            foreach ($creditmemo->getAllItems() as $item) {
                $orderItem = $item->getOrderItem();
                if ($orderItem->isDummy()) {
                    continue;
                }

                $orderItemAmount = (double)$orderItem->getAwAfptcInvoiced();
                $baseOrderItemAmount = (double)$orderItem->getBaseAwAfptcInvoiced();
                $orderItemQty = $orderItem->getAwAfptcQtyInvoiced();

                if ($orderItemAmount && $orderItemQty) {
                    // Resolve rounding problems
                    $amount = $orderItemAmount - $orderItem->getAwAfptcRefunded();
                    $baseAmount = $baseOrderItemAmount - $orderItem->getBaseAwAfptcRefunded();
                    $activeQty = $orderItemQty - $orderItem->getAwAfptcQtyRefunded();
                    $qtyToRefund = $item->getQty() > $activeQty ? $activeQty : $item->getQty();

                    if (!$item->isLast()) {
                        $amount = $creditmemo->roundPrice(
                            $amount / $activeQty * $qtyToRefund,
                            'regular',
                            true
                        );
                        $baseAmount = $creditmemo->roundPrice(
                            $baseAmount / $activeQty * $qtyToRefund,
                            'base',
                            true
                        );
                    }

                    $item->setAwAfptcAmount($amount);
                    $item->setBaseAwAfptcAmount($baseAmount);

                    $orderItem->setAwAfptcRefunded(
                        $orderItem->getAwAfptcRefunded() + $item->getAwAfptcAmount()
                    );
                    $orderItem->setBaseAwAfptcRefunded(
                        $orderItem->getBaseAwAfptcRefunded() + $item->getBaseAwAfptcAmount()
                    );
                    $orderItem->setAwAfptcQtyRefunded(
                        $orderItem->getAwAfptcQtyRefunded() + $qtyToRefund
                    );

                    $totalAmount += $amount;
                    $baseTotalAmount += $baseAmount;
                }
            }

            if ($baseTotalAmount > 0) {
                $creditmemo->setBaseAwAfptcAmount(-$baseTotalAmount);
                $creditmemo->setAwAfptcAmount(-$totalAmount);

                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $totalAmount);
                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $baseTotalAmount);
            }
        }

        if ($creditmemo->getGrandTotal() <= 0) {
            $creditmemo->setAllowZeroGrandTotal(true);
        }

        return $this;
    }
}
