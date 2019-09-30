<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver;

use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Option\ConfigurationPool;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Psr\Log\LoggerInterface;
use Magento\Quote\Api\Data\ProductOptionInterfaceFactory;
use Magento\Quote\Api\Data\ProductOptionInterface;

/**
 * Class Option
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver
 */
class Option
{
    /**
     * @var ConfigurationPool
     */
    private $configurationPool;

    /**
     * @var ProductOptionInterfaceFactory
     */
    private $productOptionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ConfigurationPool $configurationPool
     * @param ProductOptionInterfaceFactory $productOptionFactory
     * @param ProductRepositoryInterface $productRepository
     * @param DataObjectHelper $dataObjectHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        ConfigurationPool $configurationPool,
        ProductOptionInterfaceFactory $productOptionFactory,
        ProductRepositoryInterface $productRepository,
        DataObjectHelper $dataObjectHelper,
        LoggerInterface $logger
    ) {
        $this->configurationPool = $configurationPool;
        $this->productOptionFactory = $productOptionFactory;
        $this->productRepository = $productRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->logger = $logger;
    }

    /**
     * Resolve active item options
     *
     * @param AbstractItem $item
     * @return ProductOptionInterface
     */
    public function resolveActiveOptions($item)
    {
        $customOptions = [];
        $configurationOptions = [];
        $productType = $item->getProduct()->getTypeId();
        if ($this->configurationPool->hasConfiguration($productType)) {
            try {
                $configurationOptions = $this->configurationPool
                    ->getConfiguration($productType)
                    ->getActiveOptions($item);
            } catch (\Exception $e) {
                $this->logger->error($e);
            }
        }
        $options = array_merge($customOptions, $configurationOptions);

        return $this->createOptionObject($options);
    }

    /**
     * Resolve item options
     *
     * @param RuleMetadataPromoProductInterface $promoProduct
     * @param array $options
     * @return ProductOptionInterface
     */
    public function resolveOptions($promoProduct, $options)
    {
        try {
            $configurationOptions = [];
            $product = $this->productRepository->get($promoProduct->getProductSku());
            $productType = $product->getTypeId();
            if ($this->configurationPool->hasConfiguration($productType)) {
                $configurationOptions = $this->configurationPool
                    ->getConfiguration($productType)
                    ->processOptions($options);
            }
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
        $resolvedOption = $this->createOptionObject($configurationOptions);

        return $resolvedOption;
    }

    /**
     * Create option object
     *
     * @param array $options
     * @return ProductOptionInterface
     */
    private function createOptionObject($options)
    {
        $resolvedOption = $this->productOptionFactory->create();
        if (!empty($options)) {
            $resolvedOptionData = [
                ProductOptionInterface::EXTENSION_ATTRIBUTES_KEY => $options
            ];
            $this->dataObjectHelper->populateWithArray(
                $resolvedOption,
                $resolvedOptionData,
                ProductOptionInterface::class
            );
        }

        return $resolvedOption;
    }
}
