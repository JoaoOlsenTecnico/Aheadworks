<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Checkout\Summary\Item\ImageData;

use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Framework\App\Area;
use Aheadworks\Afptc\Api\Data\CheckoutSummaryProductImageInterfaceFactory;
use Aheadworks\Afptc\Api\Data\CheckoutSummaryProductImageInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Catalog\Helper\Image as ImageHelper;

/**
 * Class Collector
 *
 * @package Aheadworks\Afptc\Model\Checkout\Summary\Item\ImageData
 */
class Collector
{
    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @var ItemResolverInterface
     */
    private $itemResolver;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $appEmulation;

    /**
     * @var CheckoutSummaryProductImageInterfaceFactory
     */
    private $imageDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param ItemResolverInterface $itemResolver
     * @param ImageHelper $imageHelper
     * @param Emulation $appEmulation
     * @param CheckoutSummaryProductImageInterfaceFactory $imageDataFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ItemResolverInterface $itemResolver,
        ImageHelper $imageHelper,
        Emulation $appEmulation,
        CheckoutSummaryProductImageInterfaceFactory $imageDataFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->imageHelper = $imageHelper;
        $this->appEmulation = $appEmulation;
        $this->itemResolver = $itemResolver;
        $this->imageDataFactory = $imageDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Get image data object
     *
     * @param Item $item
     * @return CheckoutSummaryProductImageInterface
     */
    public function getImageDataObject($item)
    {
        $product = $this->itemResolver->getFinalProduct($item);

        $this->appEmulation->startEnvironmentEmulation($product->getStoreId(), Area::AREA_FRONTEND, true);
        $imageHelper = $this->imageHelper->init($product, 'mini_cart_product_thumbnail');
        $this->appEmulation->stopEnvironmentEmulation();

        $productImageData = [
            'src' => $imageHelper->getUrl(),
            'alt' => $imageHelper->getLabel(),
            'width' => $imageHelper->getWidth(),
            'height' => $imageHelper->getHeight(),
        ];

        $imageDataObject = $this->imageDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $imageDataObject,
            $productImageData,
            CheckoutSummaryProductImageInterface::class
        );

        return $imageDataObject;
    }
}
