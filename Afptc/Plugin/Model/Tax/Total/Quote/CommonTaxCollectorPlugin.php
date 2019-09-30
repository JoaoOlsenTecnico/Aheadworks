<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\Tax\Total\Quote;

use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory;
use Magento\Tax\Model\Sales\Total\Quote\CommonTaxCollector;

/**
 * Class CommonTaxCollectorPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Model\Tax\Total\Quote
 */
class CommonTaxCollectorPlugin
{
    /**
     * Update discount amount value
     *
     * @param CommonTaxCollector $subject
     * @param \Closure $proceed
     * @param QuoteDetailsItemInterfaceFactory $itemDataObjectFactory
     * @param AbstractItem $item
     * @param bool $priceIncludesTax
     * @param bool $useBaseCurrency
     * @param string $parentCode
     * @return \Magento\Tax\Api\Data\QuoteDetailsItemInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundMapItem(
        $subject,
        $proceed,
        QuoteDetailsItemInterfaceFactory $itemDataObjectFactory,
        AbstractItem $item,
        $priceIncludesTax,
        $useBaseCurrency,
        $parentCode = null
    ) {
        $itemDataObject = $proceed(
            $itemDataObjectFactory,
            $item,
            $priceIncludesTax,
            $useBaseCurrency,
            $parentCode
        );

        if ($useBaseCurrency) {
            $itemDataObject->setDiscountAmount(
                $itemDataObject->getDiscountAmount() + $item->getBaseAwAfptcAmount()
            );
        } else {
            $itemDataObject->setDiscountAmount(
                $itemDataObject->getDiscountAmount() + $item->getAwAfptcAmount()
            );
        }
        return $itemDataObject;
    }
}
