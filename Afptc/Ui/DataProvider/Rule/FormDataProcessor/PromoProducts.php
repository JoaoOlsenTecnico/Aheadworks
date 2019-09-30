<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\PromoProducts\OptionProcessor;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;

/**
 * Class PromoProducts
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor
 */
class PromoProducts implements ProcessorInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var OptionProcessor
     */
    private $optionProcessor;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param ImageHelper $imageHelper
     * @param OptionProcessor $optionProcessor
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ImageHelper $imageHelper,
        OptionProcessor $optionProcessor
    ) {
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
        $this->optionProcessor = $optionProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareEntityData($data)
    {
        $result = [];

        if (isset($data[RuleInterface::PROMO_PRODUCTS])) {
            $promoProducts = $data[RuleInterface::PROMO_PRODUCTS];
            foreach ($promoProducts as $index => $promoProduct) {
                $product = $this->productRepository->get($promoProduct[RulePromoProductInterface::PRODUCT_SKU]);

                $productData = [
                    'id' => $product->getId(),
                    'position' => $index + 1,
                    ProductInterface::NAME => $product->getName(),
                    'thumbnail' => $this->imageHelper->init($product, 'product_listing_thumbnail')->getUrl(),
                    RulePromoProductInterface::PRODUCT_SKU => $product->getSku(),
                    'type' => $product->getTypeId(),
                ];

                $productData = $this->optionProcessor->prepareProductOptions($productData, $promoProduct, $product);
                $result[] = $productData;
            }
        }

        $data[RuleInterface::PROMO_PRODUCTS] = $result;
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareMetaData($meta)
    {
        return $meta;
    }
}
