<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor;

use Magento\Quote\Model\Quote\Address;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor\CartAttribute\Mapper;

/**
 * Class CartAttribute
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor
 */
class CartAttribute implements ProcessorInterface
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * @var Mapper
     */
    private $attributeMapper;

    /**
     * @param Mapper $attributeMapper
     * @param array $attributes
     */
    public function __construct(
        Mapper $attributeMapper,
        array $attributes = []
    ) {
        $this->attributeMapper = $attributeMapper;
        $this->attributes = $attributes;
    }

    /**
     * Exclude cart attributes for promo products
     *
     * @param Address $address
     * @param array $data
     * @return array
     */
    public function process($address, $data)
    {
        foreach ($this->attributes as $addressAttribute => $itemAttribute) {
            $attributeValue = $address->getData($addressAttribute);

            foreach ($address->getAllVisibleItems() as $item) {
                if ($item->getAwAfptcIsPromo()) {
                    $attributeValue -= $item->getData($this->attributeMapper->mapAttribute($itemAttribute, $item));
                }
            }
            $data[$addressAttribute] = $attributeValue;
        }

        return $data;
    }
}
