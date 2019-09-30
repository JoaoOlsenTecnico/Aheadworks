<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct\DataCollector;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Aheadworks\Afptc\Model\Rule\Condition\Loader as ConditionLoader;
use Aheadworks\Afptc\Model\Source\Rule\Scenario;

/**
 * Class Validator
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct\DataCollector
 */
class Validator
{
    /**
     * @var ConditionLoader
     */
    private $conditionLoader;

    /**
     * @param ConditionLoader $conditionLoader
     */
    public function __construct(
        ConditionLoader $conditionLoader
    ) {
        $this->conditionLoader = $conditionLoader;
    }

    /**
     * Check if rule is valid to be processed
     *
     * @param RuleInterface $rule
     * @return bool
     */
    public function isRuleValid($rule)
    {
        return $rule->isActive()
            && $rule->getCartConditions()
            && $rule->getScenario() == Scenario::BUY_X_GET_Y;
    }

    /**
     * Check if input data is valid
     *
     * @param ProductInterface $product
     * @param RuleInterface $rule
     * @return bool
     */
    public function isInputDataValid($rule, $product)
    {
        return $this->isRuleValid($rule)
            && $this->isProductValid($product, $rule);
    }

    /**
     * Check if product is valid
     *
     * @param ProductInterface $product
     * @param RuleInterface $rule
     * @return bool
     */
    private function isProductValid($product, $rule)
    {
        $productConditionRule = $this->conditionLoader->loadProductCondition($rule);
        $productConditions = $productConditionRule->getConditions();

        return $productConditions->validate($product);
    }
}
