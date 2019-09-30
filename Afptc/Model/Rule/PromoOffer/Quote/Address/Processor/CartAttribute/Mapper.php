<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor\CartAttribute;

use Aheadworks\Afptc\Model\Config;
use Aheadworks\Afptc\Model\Source\Rule\Validation\PriceType;
use Magento\Quote\Model\Quote\Address\Item;

/**
 * Class Mapper
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor\CartAttribute
 */
class Mapper
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $map = [
        'base_row_total' => [
            PriceType::INCLUDING_TAX => 'base_row_total_incl_tax',
            PriceType::EXCLUDING_TAX => 'base_row_total'
        ],
        'orig_base_row_total' => [
            PriceType::INCLUDING_TAX => 'orig_base_row_total_incl_tax',
            PriceType::EXCLUDING_TAX => 'orig_base_row_total'
        ]
    ];

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Map attribute
     *
     * @param string $attribute
     * @param Item $item
     * @return string
     */
    public function mapAttribute($attribute, $item)
    {
        $map = $this->getMap();
        $validationType = $this->config->getSubtotalValidationType();
        if ($item->hasData('orig_' . $attribute)) {
            $attribute = 'orig_' . $attribute;
        }
        if (isset($map[$attribute][$validationType])) {
            $attribute = $map[$attribute][$validationType];
        }

        return $attribute;
    }

    /**
     * Get attribute map
     *
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }
}
