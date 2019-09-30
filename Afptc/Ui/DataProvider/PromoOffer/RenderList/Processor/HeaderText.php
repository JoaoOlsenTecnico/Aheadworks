<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor;

use Aheadworks\Afptc\Model\Config;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class HeaderText
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor
 */
class HeaderText implements ProcessorInterface
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
    public function prepareRender($promoOfferRender, $metadataRules)
    {
        $text = '';
        $ruleCounts = count($metadataRules);
        if ($ruleCounts) {
            $storeId = $this->storeManager->getStore()->getId();
            $text = $ruleCounts > 1
                ? $this->config->getDefaultOfferPopupTitle($storeId)
                : reset($metadataRules)->getRule()->getPopupHeaderText();
        }
        $promoOfferRender->setHeaderText($text);
    }
}
