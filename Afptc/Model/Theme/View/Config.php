<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Theme\View;

use Magento\Framework\View\Config as ViewConfig;
use Magento\Catalog\Helper\Image as ImageHelper;

/**
 * Class Config
 *
 * @package Aheadworks\Afptc\Model\Theme\View
 */
class Config
{
    /**
     * Module name used to retrieve data from view config
     */
    const MODULE_NAME = 'Aheadworks_Afptc';

    /**
     * Path in view config
     */
    const PROMO_CONFIG_PATH = 'promo_info/placement/';

    /**#@+
     * Image types in view config
     */
    const PRODUCT_IMAGE = '_product_image';
    const POPUP_IMAGE = '_popup_image';
    /**#@-*/

    /**#@+
     * Image attributes
     */
    const IMAGE_WIDTH = 'width';
    const IMAGE_HEIGHT = 'height';
    /**#@-*/

    /**
     * @var ViewConfig
     */
    private $viewConfig;

    /**
     * @param ViewConfig $viewConfig
     */
    public function __construct(
        ViewConfig $viewConfig
    ) {
        $this->viewConfig = $viewConfig;
    }

    /**
     * Get var value
     *
     * @param string $var
     * @param array $params
     * @return array
     */
    public function getVarValue($var, $params = [])
    {
        $viewConfig = $this->viewConfig->getViewConfig($params);
        return $viewConfig->getVarValue(self::MODULE_NAME, $var);
    }

    /**
     * Get media attributes
     *
     * @param string $mediaType
     * @param string $mediaId
     * @param array $params
     * @return array
     */
    public function getMediaAttributes($mediaType, $mediaId, $params = [])
    {
        $viewConfig = $this->viewConfig->getViewConfig($params);
        return $viewConfig->getMediaAttributes(self::MODULE_NAME, $mediaType, $mediaId);
    }

    /**
     * Get promo config
     *
     * @param string $placement
     * @return array
     */
    public function getPromoConfig($placement)
    {
        return $this->getVarValue(self::PROMO_CONFIG_PATH . $placement);
    }

    /**
     * Get image attributes by placement
     *
     * @param string $imageType
     * @param string $placement
     * @return array
     */
    public function getImageAttributesByPlacement($imageType, $placement)
    {
        return $this->getMediaAttributes(
            ImageHelper::MEDIA_TYPE_CONFIG_NODE,
            $placement . $imageType,
            []
        );
    }
}
