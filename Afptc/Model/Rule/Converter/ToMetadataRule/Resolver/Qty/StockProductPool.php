<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty;

/**
 * Class StockProductPool
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty
 */
class StockProductPool
{
    /**
     * Default product stock
     */
    const DEFAULT_STOCK = 'default';

    /**
     * @var array
     */
    private $stockProducts = [];

    /**
     * @param array $stockProducts
     */
    public function __construct(
        $stockProducts = []
    ) {
        $this->stockProducts = $stockProducts;
    }

    /**
     * Get stock product instance
     *
     * @param string $productType
     * @return StockProductInterface
     * @throws \Exception
     */
    public function getStockProduct($productType)
    {
        if (!isset($this->stockProducts[$productType])) {
            $productType = self::DEFAULT_STOCK;
        }
        $stockProductInstance = $this->stockProducts[$productType];
        if (!$stockProductInstance instanceof StockProductInterface) {
            throw new \Exception(
                sprintf('Stock product instance %s does not implement required interface.', $productType)
            );
        }

        return $stockProductInstance;
    }
}
