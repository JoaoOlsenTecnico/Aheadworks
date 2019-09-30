<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRender\ImageInterface;
use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRender\ImageInterfaceFactory;
use Magento\Catalog\Helper\Image as ImageHelper;

/**
 * Class Image
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor
 */
class Image implements ProcessorInterface
{
    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @var ImageInterfaceFactory
     */
    private $imageFactory;

    /**
     * @var string
     */
    private $imageId;

    /**
     * @param ImageHelper $imageHelper
     * @param ImageInterfaceFactory $imageFactory
     * @param string $imageId
     */
    public function __construct(
        ImageHelper $imageHelper,
        ImageInterfaceFactory $imageFactory,
        $imageId = 'cart_page_product_thumbnail'
    ) {
        $this->imageHelper = $imageHelper;
        $this->imageFactory = $imageFactory;
        $this->imageId = $imageId;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareProductData($product, $ruleMetadata, $ruleMetadataPromoProduct, $productRender)
    {
        $imageHelper = $this->imageHelper->init($product, $this->imageId);
        /** @var ImageInterface $imageRender */
        $imageRender = $this->imageFactory->create();
        $imageRender
            ->setSrc($imageHelper->getUrl())
            ->setAlt($imageHelper->getLabel())
            ->setWidth($imageHelper->getWidth())
            ->setHeight($imageHelper->getHeight());

        $productRender->setImage($imageRender);
    }
}
