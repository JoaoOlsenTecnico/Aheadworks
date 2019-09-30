<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts\ProductType\Configurable;
use Magento\Framework\Api\ExtensibleDataInterface;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;

/**
 * Class PromoProduct
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor
 */
class PromoProduct implements ProcessorInterface
{
    /**
     * Prepare promo products sku collection for save
     *
     * @param array $data
     * @return array
     */
    public function process($data)
    {
        $result = [];
        if (isset($data[RuleInterface::PROMO_PRODUCTS])) {
            $promoProducts = $data[RuleInterface::PROMO_PRODUCTS];

            foreach ($promoProducts as $promoProduct) {
                $result[] = [
                    RulePromoProductInterface::PRODUCT_SKU => $promoProduct[RulePromoProductInterface::PRODUCT_SKU],
                    RulePromoProductInterface::OPTION => $this->prepareOption($promoProduct),
                    RulePromoProductInterface::POSITION => $promoProduct[RulePromoProductInterface::POSITION]
                ];
            }
        }
        $data[RuleInterface::PROMO_PRODUCTS] = $result;
        return $data;
    }

    /**
     * Prepare option
     *
     * @param array $promoProduct
     * @return array|null
     */
    private function prepareOption($promoProduct)
    {
        $result = null;
        if (isset($promoProduct[RulePromoProductInterface::OPTION][Configurable::CONFIGURABLE_ITEM_OPTIONS])) {
            $result = [
                ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY => [
                    Configurable::CONFIGURABLE_ITEM_OPTIONS =>
                        $promoProduct[RulePromoProductInterface::OPTION][Configurable::CONFIGURABLE_ITEM_OPTIONS]
                ]
            ];
        }
        return $result;
    }
}
