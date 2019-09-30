<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor;

use Aheadworks\Afptc\Model\Config;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class OptionToggleSettings
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor
 */
class ToggleOptionSettings implements ProcessorInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Config $config,
        StoreManagerInterface $storeManager
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareProductData($product, $ruleMetadata, $ruleMetadataPromoProduct, $productRender)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $isOptionBlockHidden = $this->config->isOptionBlockHidden($storeId);
        $productRender->setIsOptionBlockHidden($isOptionBlockHidden);
    }
}
