<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Checkout\Klarna\Orderline;

use Klarna\Core\Api\BuilderInterface;
use Magento\Quote\Model\Quote;
use Klarna\Core\Model\Checkout\Orderline\AbstractLine;

/**
 * Class Afptc
 *
 * @package Aheadworks\Afptc\Model\Checkout\Klarna\Orderline
 */
class Afptc extends AbstractLine
{
    /**
     * Checkout item type
     */
    const ITEM_TYPE_AFPTC = 'discount';

    /**
     * Discount is a line item collector
     *
     * @var bool
     */
    protected $isTotalCollector = false;

    /**
     * Collect totals process
     *
     * @param BuilderInterface $checkout
     * @return $this
     */
    public function collect(BuilderInterface $checkout)
    {
        /** @var Quote $quote */
        $quote = $checkout->getObject();
        $totals = $quote->getTotals();

        if (is_array($totals) && isset($totals['aw_afptc'])) {
            $total = $totals['aw_afptc'];
            $value = $this->helper->toApiFloat($total->getValue());
            $checkout->addData([
                'aw_afptc_unit_price'   => $value,
                'aw_afptc_tax_rate'     => 0,
                'aw_afptc_total_amount' => $value,
                'aw_afptc_tax_amount'   => 0,
                'aw_afptc_title'        => $total->getTitle(),
                'aw_afptc_reference'    => $total->getCode()
            ]);
        }

        return $this;
    }

    /**
     * Add order details to checkout request
     *
     * @param BuilderInterface $checkout
     * @return $this
     */
    public function fetch(BuilderInterface $checkout)
    {
        if ($checkout->getAwAfptcReference() && $checkout->getAwAfptcTotalAmount() !== 0) {
            $checkout->addOrderLine(
                [
                    'type'             => self::ITEM_TYPE_AFPTC,
                    'reference'        => $checkout->getAwAfptcReference(),
                    'name'             => $checkout->getAwAfptcTitle(),
                    'quantity'         => 1,
                    'unit_price'       => $checkout->getAwAfptcUnitPrice(),
                    'tax_rate'         => $checkout->getAwAfptcTaxRate(),
                    'total_amount'     => $checkout->getAwAfptcTotalAmount(),
                    'total_tax_amount' => $checkout->getAwAfptcTaxAmount(),
                ]
            );
        }

        return $this;
    }
}
