<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Validator;

use Aheadworks\Afptc\Model\Source\Rule\Discount\Type;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\AbstractValidator;
use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class PromoProducts
 *
 * @package Aheadworks\Afptc\Model\Rule\Validator
 */
class PromoProduct extends AbstractValidator
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * Validation
     *
     * @param RuleInterface $rule
     * @return bool
     */
    public function isValid($rule)
    {
        $qtyToGive = (int) $rule->getQtyToGive();
        $promoProductsSku = $rule->getPromoProducts();

        if ($qtyToGive > count($promoProductsSku)) {
            $this->_addMessages([
                __('Count of products to offer must be less than number of selected promo products.')
            ]);
        }
        if ($rule->getDiscountType() == Type::FIXED_PRICE) {
            $this->validatePrices($rule);
        }

        return empty($this->getMessages());
    }

    /**
     * Validate promo product prices
     *
     * @param RuleInterface $rule
     * @return void
     */
    private function validatePrices($rule)
    {
        $fixedAmount = $rule->getDiscountAmount();
        $promoProducts = $rule->getPromoProducts();
        foreach ($promoProducts as $product) {
            try {
                $catalogProduct = $this->productRepository->get($product->getProductSku());
                if ($catalogProduct->getTypeId() == Configurable::TYPE_CODE) {
                    $childMinPrice = null;
                    /** @var Product $childProduct */
                    foreach ($catalogProduct->getTypeInstance()->getUsedProducts($catalogProduct) as $childProduct) {
                        $childMinPrice = $childMinPrice !== null
                            ? min($childMinPrice, $childProduct->getPrice())
                            : $childProduct->getPrice();
                    }
                    $price = $childMinPrice;
                } else {
                    $price = $catalogProduct->getPrice();
                }
            } catch (LocalizedException $e) {
                $price = 0;
            }

            if ($price <= $fixedAmount) {
                $this->_addMessages([__(
                    'Product with SKU = %1 has less or equal price than defined fixed amount.',
                    $product->getProductSku()
                )]);
            }
        }
    }
}
