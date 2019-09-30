<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Condition\Cart\Product;

use Aheadworks\Afptc\Model\Rule\Condition\Cart\Product;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\Complete;

/**
 * Trait OptionsList
 *
 * @package Aheadworks\Afptc\Model\Rule\Condition\Cart\Product
 */
trait OptionsList
{
    /**
     * Return conditions
     *
     * @return array|mixed
     */
    public function getConditions()
    {
        if ($this->getData($this->getPrefix()) === null) {
            $this->setData($this->getPrefix(), []);
        }
        return $this->getData($this->getPrefix());
    }

    /**
     * Return list of available options
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $productAttributes = $this->_ruleConditionProd->loadAttributeOptions()->getAttributeOption();
        $pAttributes = [];
        $iAttributes = [];
        foreach ($productAttributes as $code => $label) {
            if (strpos($code, 'quote_item_') === 0) {
                $iAttributes[] = [
                    'value' => Product::class . '|' . $code,
                    'label' => $label,
                ];
            } else {
                $pAttributes[] = [
                    'value' => Product::class . '|' . $code,
                    'label' => $label,
                ];
            }
        }

        $conditions = [
            ['value' => '', 'label' => __('Please choose a condition to add.')]
        ];

        $conditions = array_merge_recursive(
            $conditions,
            [
                [
                    'value' => Combine::class,
                    'label' => __('Conditions Combination'),
                ]
            ]
        );

        if ($this->getPrefix() == Complete::CONDITION_PREFIX) {
            $conditions = array_merge_recursive(
                $conditions,
                [
                    ['label' => __('Cart Item Attribute'), 'value' => $iAttributes]
                ]
            );
        }

        $conditions = array_merge_recursive(
            $conditions,
            [
                ['label' => __('Product Attribute'), 'value' => $pAttributes]
            ]
        );

        return $conditions;
    }
}
