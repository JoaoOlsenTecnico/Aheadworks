<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option;

/**
 * Class ConfigurationPool
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Configuration
 */
class ConfigurationPool
{
    /**
     * @var array
     */
    private $configurations = [];

    /**
     * @param array $configurations
     */
    public function __construct(
        $configurations = []
    ) {
        $this->configurations = $configurations;
    }

    /**
     * Get configuration instance
     *
     * @param string $productType
     * @return ConfigurationInterface
     * @throws \Exception
     */
    public function getConfiguration($productType)
    {
        if (!isset($this->configurations[$productType])) {
            throw new \Exception(sprintf('Unknown configuration: %s requested', $productType));
        }
        $configurationInstance = $this->configurations[$productType];
        if (!$configurationInstance instanceof ConfigurationInterface) {
            throw new \Exception(
                sprintf('Configuration instance %s does not implement required interface.', $productType)
            );
        }

        return $configurationInstance;
    }

    /**
     * Check if configuration for product type exists
     *
     * @param string $productType
     * @return bool
     */
    public function hasConfiguration($productType)
    {
        return isset($this->configurations[$productType]);
    }
}
