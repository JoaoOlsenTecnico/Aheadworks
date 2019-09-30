<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\Cart\Totals;

use Aheadworks\Afptc\Model\Cart\Promo\Item\QtyLabelResolver;
use Magento\Quote\Api\Data\TotalsItemExtensionInterfaceFactory;
use Magento\Quote\Api\Data\TotalsItemInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Cart\Totals\ItemConverter;
use Aheadworks\Afptc\Model\Checkout\Summary\Item\ImageData\Collector;
use Aheadworks\Afptc\Model\Cart\Item\Renderer\PriceAdjustment;

/**
 * Class ItemConverterPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Model\Cart\Totals
 */
class ItemConverterPlugin
{
    /**
     * @var QtyLabelResolver
     */
    private $qtyLabelResolver;

    /**
     * @var TotalsItemExtensionInterfaceFactory
     */
    private $totalsItemExtensionFactory;

    /**
     * @var Collector
     */
    private $imageDataCollector;

    /**
     * @var PriceAdjustment
     */
    private $priceAdjustment;

    /**
     * @param QtyLabelResolver $qtyLabelResolver
     * @param TotalsItemExtensionInterfaceFactory $totalsItemExtensionFactory
     * @param Collector $imageDataCollector
     * @param PriceAdjustment $priceAdjustment
     */
    public function __construct(
        QtyLabelResolver $qtyLabelResolver,
        TotalsItemExtensionInterfaceFactory $totalsItemExtensionFactory,
        Collector $imageDataCollector,
        PriceAdjustment $priceAdjustment
    ) {
        $this->qtyLabelResolver = $qtyLabelResolver;
        $this->totalsItemExtensionFactory = $totalsItemExtensionFactory;
        $this->imageDataCollector = $imageDataCollector;
        $this->priceAdjustment = $priceAdjustment;
    }

    /**
     * Update discount amount value
     *
     * @param ItemConverter $subject
     * @param \Closure $proceed
     * @param Item $item
     * @return TotalsItemInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundModelToDataObject($subject, $proceed, $item)
    {
        $this->priceAdjustment->adjustForRendering($item);
        /** @var TotalsItemInterface $itemData */
        $itemData = $proceed($item);

        $extensionAttributes = $itemData->getExtensionAttributes()
            ? $itemData->getExtensionAttributes()
            : $this->totalsItemExtensionFactory->create();
        $extensionAttributes->setAwAfptcQtyLabel($this->qtyLabelResolver->resolve($item));
        $extensionAttributes->setAwAfptcImageData($this->imageDataCollector->getImageDataObject($item));

        $itemData->setExtensionAttributes($extensionAttributes);

        return $itemData;
    }
}
