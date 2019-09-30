<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Validator\Option as OptionValidator;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Children\Validator as ChildrenValidator;

/**
 * Class Validator
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items
 */
class Validator
{
    /**
     * @var ChildrenValidator
     */
    private $childrenValidator;

    /**
     * @var OptionValidator
     */
    private $optionValidator;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param ChildrenValidator $childrenValidator
     * @param OptionValidator $optionValidator
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ChildrenValidator $childrenValidator,
        OptionValidator $optionValidator,
        ProductRepositoryInterface $productRepository
    ) {
        $this->childrenValidator = $childrenValidator;
        $this->optionValidator = $optionValidator;
        $this->productRepository = $productRepository;
    }

    /**
     * Check if quote item is valid by promo products
     *
     * @param AbstractItem $item
     * @param RulePromoProductInterface[]|RulePromoProductInterface $promoProducts
     * @return bool
     */
    public function isValidByPromoProducts($item, $promoProducts)
    {
        $isValid = false;
        $promoProducts = is_array($promoProducts) ? $promoProducts : [$promoProducts];
        foreach ($promoProducts as $promoProduct) {
            if ($this->isValidBySku($item, $promoProduct->getProductSku())
                && $this->optionValidator->isValid($item, $promoProduct)
            ) {
                $isValid = true;
                break;
            }
        }
        return $isValid;
    }

    /**
     * Check if quote item is valid by sku
     *
     * @param AbstractItem $item
     * @param array|string $sku
     * @return bool
     */
    public function isValidBySku($item, $sku)
    {
        $isValid = false;
        if ($item->getParentItem()) {
            $isValid = false;
            // Bundle product is valid here
        } elseif ($this->compare($item, $sku)) {
            $isValid = true;
            // Bundle type is return false
        } elseif ($this->childrenValidator->isValidationRequired($item)) {
            $childItems = $item->getChildren();
            // Configurable child is valid here
            foreach ($childItems as $childItem) {
                if ($this->compare($childItem, $sku)) {
                    $isValid = true;
                    break;
                }
            }
        }

        return $isValid;
    }

    /**
     * Is product available
     *
     * @param string $sku
     * @return bool
     */
    public function isAvailableProduct($sku)
    {
        try {
            /** @var Product $product */
            $product = $this->productRepository->get($sku);
            /** @var \Magento\Catalog\Model\Product\Type\AbstractType $productType */
            $productType = $product->getTypeInstance();

            $isAvailable = $product->isVisibleInSiteVisibility()
                && $product->isVisibleInCatalog() && $productType->isSalable($product);
        } catch (NoSuchEntityException $e) {
            $isAvailable = false;
        }

        return $isAvailable;
    }

    /**
     * Compare sku by type
     *
     * @param AbstractItem $item
     * @param array|string $sku
     * @return bool
     */
    private function compare($item, $sku)
    {
        $result = false;
        $itemSku = $this->getItemSku($item);
        if (is_array($sku)) {
            $result = in_array($itemSku, $sku);
        } elseif (is_string($sku)) {
            $result = $itemSku == $sku;
        }

        return $result;
    }

    /**
     * Retrieve item sku
     *
     * @param AbstractItem $item
     * @return string
     */
    private function getItemSku($item)
    {
        return $item->getProduct()->getData('sku');
    }
}
