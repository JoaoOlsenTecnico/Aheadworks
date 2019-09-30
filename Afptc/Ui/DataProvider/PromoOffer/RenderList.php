<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigInterfaceFactory;
use Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigInterface;
use Aheadworks\Afptc\Api\Data\PromoOfferRenderInterfaceFactory;
use Aheadworks\Afptc\Api\Data\PromoOfferRenderInterface;
use Aheadworks\Afptc\Api\PromoOfferRenderListInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\ListingBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor\Composite as RenderProcessor;

/**
 * Class RenderList
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer
 */
class RenderList implements PromoOfferRenderListInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ListingBuilder
     */
    private $productListingBuilder;

    /**
     * @var RuleConfigInterfaceFactory
     */
    private $ruleConfigFactory;

    /**
     * @var PromoOfferRenderInterfaceFactory
     */
    private $promoOfferRenderFactory;

    /**
     * @var RenderProcessor
     */
    private $processor;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ListingBuilder $productListingBuilder
     * @param RuleConfigInterfaceFactory $ruleConfigFactory
     * @param PromoOfferRenderInterfaceFactory $promoOfferRenderFactory
     * @param RenderProcessor $processor
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ListingBuilder $productListingBuilder,
        RuleConfigInterfaceFactory $ruleConfigFactory,
        PromoOfferRenderInterfaceFactory $promoOfferRenderFactory,
        RenderProcessor $processor
    ) {
        $this->storeManager = $storeManager;
        $this->productListingBuilder = $productListingBuilder;
        $this->ruleConfigFactory = $ruleConfigFactory;
        $this->promoOfferRenderFactory = $promoOfferRenderFactory;
        $this->processor = $processor;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($metadataRules, $storeId)
    {
        $items = $rulesConfig = [];
        $store = $this->storeManager->getStore($storeId);
        foreach ($metadataRules as $metadataRule) {
            $items = array_merge($items, $this->productListingBuilder->build($metadataRule, $store));
            /** @var RuleConfigInterface $ruleConfig */
            $ruleConfig = $this->ruleConfigFactory->create();
            $ruleConfig
                ->setRuleId($metadataRule->getRule()->getRuleId())
                ->setQtyToGive($metadataRule->getAvailableQtyToGive());
            $rulesConfig[] = $ruleConfig;
        }

        /** @var PromoOfferRenderInterface $promoOfferRender */
        $promoOfferRender = $this->promoOfferRenderFactory->create();
        $promoOfferRender
            ->setWebsiteId($store->getWebsiteId())
            ->setItems($items)
            ->setRulesConfig($rulesConfig);

        $this->processor->prepareRender($promoOfferRender, $metadataRules);
        return $promoOfferRender;
    }
}
