<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Condition;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\CompleteFactory as CartRuleFactory;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\Complete as CartRule;
use Aheadworks\Afptc\Model\Rule\Condition\Product\ProductFactory as ProductRuleFactory;
use Aheadworks\Afptc\Model\Rule\Condition\Product\Product as ProductRule;
use Aheadworks\Afptc\Model\Rule\Converter\Condition\SalesToCatalog as SalesToCatalogConverter;
use Aheadworks\Afptc\Model\Rule\Converter\Condition as ConditionConverter;

/**
 * Class Loader
 *
 * @package Aheadworks\Afptc\Model\Rule\Condition
 */
class Loader
{
    /**
     * @var CartRule
     */
    private $cartRules = [];

    /**
     * @var ProductRule
     */
    private $productRules = [];

    /**
     * @var CartRuleFactory
     */
    private $cartRuleFactory;

    /**
     * @var ConditionConverter
     */
    private $conditionConverter;

    /**
     * @var ProductRuleFactory
     */
    private $productRuleFactory;

    /**
     * @var SalesToCatalogConverter
     */
    private $salesToCatalogConverter;

    /**
     * @param ConditionConverter $conditionConverter
     * @param CartRuleFactory $cartRuleFactory
     * @param ProductRuleFactory $productRuleFactory
     * @param SalesToCatalogConverter $salesToCatalogConverter
     */
    public function __construct(
        ConditionConverter $conditionConverter,
        CartRuleFactory $cartRuleFactory,
        ProductRuleFactory $productRuleFactory,
        SalesToCatalogConverter $salesToCatalogConverter
    ) {
        $this->conditionConverter = $conditionConverter;
        $this->cartRuleFactory = $cartRuleFactory;
        $this->productRuleFactory = $productRuleFactory;
        $this->salesToCatalogConverter = $salesToCatalogConverter;
    }

    /**
     * Load cart conditions by rule
     *
     * @param RuleInterface $rule
     * @return CartRule
     */
    public function loadCartCondition($rule)
    {
        if (!isset($this->cartRules[$rule->getRuleId()])) {
            $cartRule = $this->cartRuleFactory->create();
            if ($conditions = $rule->getCartConditions()) {
                $conditionArray = $this->conditionConverter->dataModelToArray($conditions);
                $cartRule->setConditions([])
                    ->getConditions()
                    ->loadArray($conditionArray);
            } else {
                $cartRule->setConditions([])
                    ->getConditions()
                    ->asArray();
            }
            $this->cartRules[$rule->getRuleId()] = $cartRule;
        }

        return $this->cartRules[$rule->getRuleId()];
    }

    /**
     * Load product conditions by rule
     *
     * @param RuleInterface $rule
     * @return ProductRule
     */
    public function loadProductCondition($rule)
    {
        if (!isset($this->productRules[$rule->getRuleId()])) {
            $productRule = $this->productRuleFactory->create();
            if ($cartConditions = $rule->getCartConditions()) {
                $productConditionArray = $this->salesToCatalogConverter->convertToArray($cartConditions);
                $productRule->setConditions([])
                    ->getConditions()
                    ->loadArray($productConditionArray);
            } else {
                $productRule->setConditions([])
                    ->getConditions()
                    ->asArray();
            }
            $this->productRules[$rule->getRuleId()] = $productRule;
        }

        return $this->productRules[$rule->getRuleId()];
    }
}
