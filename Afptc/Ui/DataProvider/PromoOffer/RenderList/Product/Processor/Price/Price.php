<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Price;

use Aheadworks\Afptc\Model\Rule\Discount\Calculator;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\ProcessorInterface;
use Magento\Catalog\Api\Data\ProductRender\PriceInfoInterface;
use Magento\Catalog\Api\Data\ProductRender\PriceInfoInterfaceFactory;
use Magento\Catalog\Model\ProductRender\FormattedPriceInfoBuilder;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Class Price
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Price
 */
class Price implements ProcessorInterface
{
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var Calculator
     */
    private $calculator;

    /**
     * @var array
     */
    private $excludeAdjustments;

    /**
     * @var PriceInfoInterfaceFactory
     */
    private $priceInfoFactory;

    /**
     * @var FormattedPriceInfoBuilder
     */
    private $formattedPriceInfoBuilder;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param Calculator $calculator
     * @param PriceInfoInterfaceFactory $priceInfoFactory
     * @param FormattedPriceInfoBuilder $formattedPriceInfoBuilder
     * @param array $excludeAdjustments
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        Calculator $calculator,
        PriceInfoInterfaceFactory $priceInfoFactory,
        FormattedPriceInfoBuilder $formattedPriceInfoBuilder,
        array $excludeAdjustments = []
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->calculator = $calculator;
        $this->excludeAdjustments = $excludeAdjustments;
        $this->priceInfoFactory = $priceInfoFactory;
        $this->formattedPriceInfoBuilder = $formattedPriceInfoBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareProductData($product, $ruleMetadata, $ruleMetadataPromoProduct, $productRender)
    {
        $priceInfo = $productRender->getPriceInfo();
        if (!$productRender->getPriceInfo()) {
            /** @var PriceInfoInterface $priceInfo */
            $priceInfo = $this->priceInfoFactory->create();
        }
        $finalPrice = $product
            ->getPriceInfo()
            ->getPrice('final_price')
            ->getAmount()
            ->getValue();
        $finalPrice = $this->calculator->calculatePrice($finalPrice, $ruleMetadata);

        $priceInfo->setFinalPrice($finalPrice);
        $priceInfo->setMinimalPrice($finalPrice);
        $priceInfo->setRegularPrice(
            $product
                ->getPriceInfo()
                ->getPrice('regular_price')
                ->getAmount()
                ->getValue()
        );
        $priceInfo->setMaxPrice($finalPrice);

        $this->formattedPriceInfoBuilder->build(
            $priceInfo,
            $productRender->getStoreId(),
            $productRender->getCurrencyCode()
        );

        $productRender->setPriceInfo($priceInfo);
    }
}
