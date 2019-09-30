<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor;

use Aheadworks\Afptc\Api\Data\PromoInterface;
use Aheadworks\Afptc\Model\Rule\Image\Manager as ImageManager;
use Magento\Framework\Stdlib\ArrayManager;
use Aheadworks\Afptc\Model\Theme\View\Config as ViewConfig;

/**
 * Class Image
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor
 */
class Image implements LayoutProcessorInterface
{
    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * @var ViewConfig
     */
    private $viewConfig;

    /**
     * @param ArrayManager $arrayManager
     * @param ImageManager $imageManager
     * @param ViewConfig $viewConfig
     */
    public function __construct(
        ArrayManager $arrayManager,
        ImageManager $imageManager,
        ViewConfig $viewConfig
    ) {
        $this->arrayManager = $arrayManager;
        $this->imageManager = $imageManager;
        $this->viewConfig = $viewConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function process($jsLayout, $promoInfoBlock, $placement, $scope)
    {
        $promo = $promoInfoBlock->getPromo();
        $component = 'components/' . $scope;
        $jsLayout = $this->arrayManager->merge(
            $component,
            $jsLayout,
            [
                'image' => $this->prepareImage($promo, $placement, ViewConfig::PRODUCT_IMAGE),
                'imageAltText' => $promo->getPromoImageAltText(),
                'popupConfig' => [
                    'image' => $this->prepareImage($promo, $placement, ViewConfig::POPUP_IMAGE),
                    'imageAltText' => $promo->getPromoImageAltText()
                ],
            ]
        );

        return $jsLayout;
    }

    /**
     * Prepare picture url
     *
     * @param PromoInterface $promo
     * @param string $placement
     * @param string $imageType
     * @return string
     */
    private function prepareImage($promo, $placement, $imageType)
    {
        $url = '';
        if ($image = $promo->getPromoImage()) {
            try {
                $imageAttributes = $this->viewConfig->getImageAttributesByPlacement($imageType, $placement);
                $url = $this->imageManager->resize(
                    $image,
                    $imageAttributes[ViewConfig::IMAGE_WIDTH],
                    $imageAttributes[ViewConfig::IMAGE_HEIGHT]
                );
            } catch (\Exception $exception) {
            }
        }
        return $url;
    }
}
