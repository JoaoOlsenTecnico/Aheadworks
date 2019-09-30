<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Total;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor\Composite as Processor;
use Aheadworks\Afptc\Model\Config;
use Aheadworks\Afptc\Model\Source\Rule\Validation\PriceType;

/**
 * Class Modifier
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer\Quote
 */
class Modifier
{
    /**
     * @var Processor
     */
    private $addressProcessor;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     * @param Processor $addressProcessor
     */
    public function __construct(
        Config $config,
        Processor $addressProcessor
    ) {
        $this->config = $config;
        $this->addressProcessor = $addressProcessor;
    }

    /**
     * Modify address
     *
     * @param Address $address
     * @param Quote $quote
     * @param Total|null $total
     * @return Address
     */
    public function modify($address, $quote, $total = null)
    {
        $clonedAddress = clone $address;
        $clonedQuote = clone $clonedAddress->getQuote();
        $clonedAddress->setQuote($clonedQuote);
        $clonedAddress->setCouponCode($quote->getCouponCode());
        $clonedAddress->setTotalQty($quote->getItemsQty());

        $subtotal = $this->prepareSubtotal($address, $total);
        $clonedAddress->setBaseSubtotal($subtotal);
        $clonedAddress->setBaseSubtotalExclPromo($subtotal);

        $processedData = $this->addressProcessor->process($clonedAddress);
        foreach ($processedData as $key => $value) {
            $clonedAddress->setData($key, $value);
        }
        $clonedAddress->getQuote()->setData('items_collection', $clonedAddress->getAllItems());

        return $clonedAddress;
    }

    /**
     * Prepare subtotal
     *
     * @param Address $address
     * @param Total|null $total
     * @return float|int
     */
    private function prepareSubtotal($address, $total = null)
    {
        if ($total) {
            $subtotal = $this->config->getSubtotalValidationType() == PriceType::INCLUDING_TAX
                ? $total->getBaseSubtotalInclTax()
                : $total->getBaseSubtotal();
        } else {
            $subtotal = $this->config->getSubtotalValidationType() == PriceType::INCLUDING_TAX
                ? $address->getBaseSubtotalTotalInclTax()
                : $address->getBaseSubtotal();
        }

        return $subtotal;
    }
}
