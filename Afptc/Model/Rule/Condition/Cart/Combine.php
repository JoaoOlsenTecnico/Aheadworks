<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Condition\Cart;

use Magento\Rule\Model\Condition\Combine as RuleCombine;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Address as ConditionAddress;
use Magento\Rule\Model\Condition\Context;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\Complete;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Product\Subselect;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Product\Found;

/**
 * Class Combine
 *
 * @package Aheadworks\Afptc\Model\Rule\Condition\Cart
 */
class Combine extends RuleCombine
{
    /**
     * @var ConditionAddress
     */
    protected $conditionAddress;

    /**
     * @param Context $context
     * @param ConditionAddress $conditionAddress
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConditionAddress $conditionAddress,
        array $data = []
    ) {
        $this->conditionAddress = $conditionAddress;
        parent::__construct($context, $data);
        $this->setType(Combine::class);
    }

    /**
     * Get new child select options
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $addressAttributes = $this->conditionAddress->loadAttributeOptions()->getAttributeOption();
        $attributes = [];
        foreach ($addressAttributes as $code => $label) {
            $attributes[] = [
                'value' => 'Aheadworks\Afptc\Model\Rule\Condition\Cart\Address|' . $code,
                'label' => $label,
            ];
        }

        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions,
            [
                [
                    'value' => Found::class,
                    'label' => __('Product attribute combination'),
                ],
                [
                    'value' => Subselect::class,
                    'label' => __('Products subselection')
                ],
                [
                    'value' => Combine::class,
                    'label' => __('Conditions combination')
                ],
            ]
        );

        if ($this->getPrefix() == Complete::CONDITION_PREFIX) {
            $conditions = array_merge_recursive(
                $conditions,
                [
                    ['label' => __('Cart Attribute'), 'value' => $attributes]
                ]
            );
        }

        return $conditions;
    }

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
}
