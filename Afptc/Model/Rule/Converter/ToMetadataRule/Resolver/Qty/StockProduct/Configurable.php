<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProduct;

use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\CatalogInventory\Model\Stock;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule\PromoProduct\Stock\Configurable as ConfigurableStockResource;

/**
 * Class Configurable
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProduct
 */
class Configurable implements StockProductInterface
{
    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ConfigurableStockResource
     */
    private $configurableResource;

    /**
     * @param StockRegistryInterface $stockRegistry
     * @param ProductRepositoryInterface $productRepository
     * @param ConfigurableStockResource $configurableResource
     */
    public function __construct(
        StockRegistryInterface $stockRegistry,
        ProductRepositoryInterface $productRepository,
        ConfigurableStockResource $configurableResource
    ) {
        $this->stockRegistry = $stockRegistry;
        $this->productRepository = $productRepository;
        $this->configurableResource = $configurableResource;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableQty($promoProduct, $storeId)
    {
        $availableQty = 0;
        try {
            $stockItem = $this->stockRegistry->getStockItemBySku($promoProduct->getProductSku(), $storeId);
            if ($stockItem->getIsInStock() == Stock::STOCK_IN_STOCK) {
                if ($promoProduct->getOption()
                    && $promoProduct->getOption()->getExtensionAttributes()->getConfigurableItemOptions()
                ) {
                    $availableQty = $this->getAvailableQtyFromOption($promoProduct, $storeId);
                } else {
                    $availableQty = $promoProduct->getQty();
                }
            }
        } catch (NoSuchEntityException $e) {
        }

        return $availableQty;
    }

    /**
     * {@inheritdoc}
     */
    public function isManageStockEnabled($promoProduct, $scopeId) : bool
    {
        $result = false;
        try {
            $stockItem = $this->stockRegistry->getStockItemBySku($promoProduct->getProductSku(), $scopeId);
            $result = $stockItem->getManageStock();
        } catch (NoSuchEntityException $e) {
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function isBackOrderAvailable($promoProduct, $scopeId) : bool
    {
        $result = false;
        try {
            $stockItem = $this->stockRegistry->getStockItemBySku($promoProduct->getProductSku(), $scopeId);
            $result = ($stockItem->getIsInStock() == Stock::STOCK_IN_STOCK
                    && $stockItem->getBackorders() != Stock::BACKORDERS_NO)
                && $stockItem->getManageStock();
        } catch (NoSuchEntityException $e) {
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableQtyForIndex($promoProduct, $storeId)
    {
        if (!$promoProduct->getOption()) {
            $availableQty = $this->configurableResource->getStockQty($promoProduct->getProductSku());
            $promoProduct->setQty($availableQty);
        }

        return $this->getAvailableQty($promoProduct, $storeId);
    }

    /**
     * Retrieve available qty from child item
     *
     * @param RuleMetadataPromoProductInterface $promoProduct
     * @param int $storeId
     * @return float
     * @throws NoSuchEntityException
     */
    private function getAvailableQtyFromOption($promoProduct, $storeId)
    {
        $availableQty = 0;
        if ($childProduct = $this->getChildProduct($promoProduct, $storeId)) {
            $childStockItem = $this->stockRegistry->getStockItemBySku($childProduct->getSku(), $storeId);
            if ($childStockItem->getIsInStock() == Stock::STOCK_IN_STOCK) {
                $availableQty = $childStockItem->getQty();
            }
        }
        return $availableQty;
    }

    /**
     * Retrieve child product from option
     *
     * @param RuleMetadataPromoProductInterface $promoProduct
     * @param int $storeId
     * @return Product|null
     * @throws NoSuchEntityException
     */
    private function getChildProduct($promoProduct, $storeId)
    {
        /** @var Product $product */
        $product = $this->productRepository->get($promoProduct->getProductSku(), false, $storeId);
        /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable $productType */
        $productType = $product->getTypeInstance();
        $options = $promoProduct->getOption()->getExtensionAttributes()->getConfigurableItemOptions();

        return $productType->getProductByAttributes($this->getAttributesInfoFromOptions($options), $product);
    }

    /**
     * Retrieve attributes info from options
     *
     * @param \Magento\ConfigurableProduct\Api\Data\ConfigurableItemOptionValueInterface[] $options
     * @return array
     */
    private function getAttributesInfoFromOptions($options)
    {
        $attributesInfo = [];
        foreach ($options as $option) {
            $attributesInfo[$option->getOptionId()] = $option->getOptionValue();
        }
        return $attributesInfo;
    }
}
