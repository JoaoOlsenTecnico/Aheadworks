<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Image\Manager;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class PathBuilder
 *
 * @package Aheadworks\Afptc\Model\Rule\Image\Manager
 */
class UrlBuilder
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    
    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Get url to media folder
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrlToMediaFolder()
    {
        $store = $this->storeManager->getStore();
        return $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }
}
