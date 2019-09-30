<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Processor;

use Aheadworks\Afptc\Model\Rule as RuleModel;
use Aheadworks\Afptc\Model\Rule\Converter\Condition as ConditionConverter;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class ProductCondition
 *
 * @package Aheadworks\Afptc\Model\Rule\Processor
 */
class ProductCondition
{
    /**
     * @var ConditionConverter
     */
    private $conditionConverter;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param ConditionConverter $conditionConverter
     * @param Json $serializer
     */
    public function __construct(
        ConditionConverter $conditionConverter,
        Json $serializer
    ) {
        $this->conditionConverter = $conditionConverter;
        $this->serializer = $serializer;
    }

    /**
     * Check product conditions data before save
     *
     * @param RuleModel $rule
     * @return RuleModel
     */
    public function beforeSave($rule)
    {
        if (is_object($rule->getCartConditions())) {
            $conditionDataModel = $rule->getCartConditions();
            $conditionArray = $this->conditionConverter->dataModelToArray($conditionDataModel);
            $rule->setCartConditions($this->serializer->serialize($conditionArray));
        }

        return $rule;
    }

    /**
     * Check product conditions data after load
     *
     * @param RuleModel $rule
     * @return RuleModel
     */
    public function afterLoad($rule)
    {
        if ($rule->getCartConditions()) {
            $conditionArray = $this->serializer->unserialize($rule->getCartConditions());
            $conditionDataModel = $this->conditionConverter->arrayToDataModel($conditionArray);
            $rule->setCartConditions($conditionDataModel);
        }

        return $rule;
    }
}
