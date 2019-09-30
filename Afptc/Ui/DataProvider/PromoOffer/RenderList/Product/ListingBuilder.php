<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product;

use Aheadworks\Afptc\Api\Data\PromoOfferRenderInterfaceFactory;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Composite as ProductProcessor;
use Magento\Framework\Exception\NoSuchEntityException;
use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterfaceFactory;
use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterface;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Class ListingBuilder
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product
 */
class ListingBuilder
{
    /**
     * @var ProductProcessor
     */
    private $productProcessor;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductRenderInterfaceFactory
     */
    private $productRenderFactory;

    /**
     * @param ProductProcessor $productProcessor
     * @param ProductRepositoryInterface $productRepository
     * @param ProductRenderInterfaceFactory $productRenderFactory
     */
    public function __construct(
        ProductProcessor $productProcessor,
        ProductRepositoryInterface $productRepository,
        ProductRenderInterfaceFactory $productRenderFactory
    ) {
        $this->productProcessor = $productProcessor;
        $this->productRepository = $productRepository;
        $this->productRenderFactory = $productRenderFactory;
    }

    /**
     * Build promo products data
     *
     * @param RuleMetadataInterface $metadataRule
     * @param StoreInterface $store
     * @return array
     */
    public function build($metadataRule, $store)
    {
        $items = [];
        foreach ($metadataRule->getPromoProducts() as $promoProduct) {
            if ($product = $this->getProduct($promoProduct->getProductSku())) {
                /** @var ProductRenderInterface $productRender */
                $productRender = $this->productRenderFactory->create();
                $productRender
                    ->setStoreId($store->getId())
                    ->setCurrencyCode($store->getCurrentCurrencyCode())
                    ->setRuleId($metadataRule->getRule()->getRuleId());

                $this->productProcessor->prepareProductData($product, $metadataRule, $promoProduct, $productRender);
                $items[] = $productRender;
            }
        }
        return $items;
    }

    /**
     * Retrieve product by sku
     *
     * @param string $sku
     * @return Product|null
     */
    private function getProduct($sku)
    {
        try {
            /** @var Product $product */
            $product = $this->productRepository->get($sku);
        } catch (NoSuchEntityException $e) {
            $product = null;
        }
        return $product;
    }
}
