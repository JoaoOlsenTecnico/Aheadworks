<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option\ConfigurationPool;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;

/**
 * Class Info
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\Product
 */
class Info
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ConfigurationPool
     */
    private $configurationPool;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param ConfigurationPool $configurationPool
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ConfigurationPool $configurationPool
    ) {
        $this->productRepository = $productRepository;
        $this->configurationPool = $configurationPool;
    }

    /**
     * Get data for product by ID
     *
     * @param int $productId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Exception
     */
    public function getData($productId)
    {
        $product = $this->productRepository->getById($productId);

        $configurationOptions = [];
        $productType = $product->getTypeId();
        if ($this->configurationPool->hasConfiguration($productType)) {
            $configurationOptions = $this->configurationPool
                ->getConfiguration($productType)
                ->getOptions($product);
        }

        $productData = [
            RulePromoProductInterface::PRODUCT_SKU => $product->getSku(),
           'type' => $product->getTypeId(),
            RulePromoProductInterface::OPTION => $configurationOptions
        ];

        return $productData;
    }
}
