<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor\QuantityNotice;

use Magento\Framework\Serialize\Serializer\Json;
use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option\ConfigurationPool;
use Psr\Log\LoggerInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option\Type as OptionType;

/**
 * Class Resolver
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor\QuantityNotice
 */
class Resolver
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
     * @var Json
     */
    private $serializer;

    /**
     * @param ConfigurationPool $configurationPool
     * @param LoggerInterface $logger
     * @param Json $serializer
     */
    public function __construct(
        ConfigurationPool $configurationPool,
        LoggerInterface $logger,
        Json $serializer
    ) {
        $this->configurationPool = $configurationPool;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * Resolve if notice should be displayed
     *
     * @param ProductRenderInterface $item
     * @return bool
     */
    public function isNeedToDisplayForItem($item)
    {
        $productType = $item->getType();
        $result = false;
        if ($this->configurationPool->hasConfiguration($productType)) {
            try {
                $configuration = $this->configurationPool->getConfiguration($productType);
                $option = $this->serializer->unserialize($item->getOption());
                $optionType = $configuration->getOptionType($option);
                $result = $optionType == OptionType::CONFIGURABLE_OPTION;
            } catch (\Exception $e) {
                $this->logger->error($e);
            }
        }
        return $result;
    }
}
