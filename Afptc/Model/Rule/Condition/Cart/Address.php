<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Condition\Cart;

use Magento\SalesRule\Model\Rule\Condition\Address as SalesRuleAddress;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Address
 *
 * @package Aheadworks\Afptc\Model\Rule\Condition\Cart
 */
class Address extends SalesRuleAddress
{
    /**
     * Load attribute options
     *
     * @return $this
     */
    public function loadAttributeOptions()
    {
        $attributes = [
            'base_subtotal' => __('Subtotal (incl. promo products)'),
            'base_subtotal_excl_promo' => __('Subtotal (excl. promo products)'),
            'total_qty' => __('Total Items Quantity'),
            'weight' => __('Total Weight'),
            'shipping_method' => __('Shipping Method'),
            'postcode' => __('Shipping Postcode'),
            'region' => __('Shipping Region'),
            'region_id' => __('Shipping State/Province'),
            'country_id' => __('Shipping Country'),
        ];

        $this->setAttributeOption($attributes);

        return $this;
    }

    /**
     * Get input type
     *
     * @return string
     */
    public function getInputType()
    {
        switch ($this->getAttribute()) {
            case 'base_subtotal':
            case 'base_subtotal_excl_promo':
            case 'weight':
            case 'total_qty':
                return 'numeric';

            case 'shipping_method':
            case 'payment_method':
            case 'country_id':
            case 'region_id':
                return 'select';
        }
        return 'string';
    }

    /**
     * Validate model
     *
     * @param AbstractModel $model
     * @return bool
     */
    public function validate(AbstractModel $model)
    {
        if (!$model->hasData($this->getAttribute())) {
            $model->load($model->getId());
        }
        $attributeValue = $model->getData($this->getAttribute());
        if (is_double($attributeValue)) {
            $attributeValue = round($attributeValue, 2);
        }

        return $this->validateAttribute($attributeValue);
    }
}
