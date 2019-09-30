<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Observer;

use Aheadworks\Afptc\Api\Data\CartInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Payment\Model\Cart;

/**
 * Class AddPaymentAfptcCardItem
 *
 * @package Aheadworks\Afptc\Observer
 */
class AddPaymentAfptcCardItem implements ObserverInterface
{
    /**
     * Merge Afptc amount into discount of payment checkout totals
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Cart $cart */
        $cart = $observer->getEvent()->getCart();
        $salesEntity = $cart->getSalesModel();
        $value = abs($salesEntity->getDataUsingMethod(CartInterface::BASE_AW_AFPTC_AMOUNT));
        if ($value > 0.0001) {
            $cart->addDiscount((double)$value);
        }
    }
}
