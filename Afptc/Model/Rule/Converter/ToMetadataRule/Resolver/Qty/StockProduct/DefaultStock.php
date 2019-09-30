<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProduct;

use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProductInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Model\Stock;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class DefaultStock
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProduct
 */
class DefaultStock implements StockProductInterface
{
    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(
        StockRegistryInterface $stockRegistry
    ) {
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableQty($promoProduct, $scopeId)
    {
        $availableQty = 0;
        try {
            $stockItem = $this->stockRegistry->getStockItemBySku($promoProduct->getProductSku(), $scopeId);
            if ($stockItem->getIsInStock() == Stock::STOCK_IN_STOCK) {
                $availableQty = $stockItem->getQty();
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
    public function getAvailableQtyForIndex($promoProduct, $scopeId)
    {
        return $this->getAvailableQty($promoProduct, $scopeId);
    }
}
