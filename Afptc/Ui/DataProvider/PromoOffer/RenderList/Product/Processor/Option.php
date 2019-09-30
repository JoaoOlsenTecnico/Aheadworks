<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor;

use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option\ConfigurationPool;
use Psr\Log\LoggerInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option\Type as OptionType;

/**
 * Class Configuration
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor
 */
class Option implements ProcessorInterface
{
    /**
     * @var ConfigurationPool
     */
    private $configurationPool;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var OptionType
     */
    private $optionType;

    /**
     * @param ConfigurationPool $configurationPool
     * @param LoggerInterface $logger
     * @param OptionType $optionType
     */
    public function __construct(
        ConfigurationPool $configurationPool,
        LoggerInterface $logger,
        OptionType $optionType
    ) {
        $this->configurationPool = $configurationPool;
        $this->logger = $logger;
        $this->optionType = $optionType;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareProductData($product, $ruleMetadata, $ruleMetadataPromoProduct, $productRender)
    {
        $configurationOptions = [];
        $productType = $product->getTypeId();
        if ($this->configurationPool->hasConfiguration($productType)) {
            try {
                $configuration = $this->configurationPool->getConfiguration($productType);
                $configurationOptions = $configuration
                    ->getOptionsByMetadata($product, $ruleMetadata, $ruleMetadataPromoProduct);
                $optionType = $configuration->getOptionType($configurationOptions);
                $productRender->setToggleOptionLinkText($this->optionType->getTextByOptionType($optionType));
            } catch (\Exception $e) {
                $this->logger->error($e);
            }
        }
        $serializedOptions = \Zend_Json::encode($configurationOptions);
        $productRender->setOption($serializedOptions);
    }
}
