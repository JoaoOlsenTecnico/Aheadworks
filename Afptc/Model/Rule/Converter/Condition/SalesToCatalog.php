<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\Condition;

use Aheadworks\Afptc\Model\Rule\Converter\Condition as ConditionConverter;
use Aheadworks\Afptc\Api\Data\ConditionInterfaceFactory;
use Aheadworks\Afptc\Api\Data\ConditionInterface;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Combine as CartCombine;
use Magento\CatalogRule\Model\Rule\Condition\Combine as CatalogRuleCombine;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Product\Found as CartFound;
use Magento\CatalogRule\Model\Rule\Condition\Product as CatalogRuleProduct;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Product as CartProduct;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Product\Subselect as CartSubselect;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Product\Combine as CartProductCombine;

/**
 * Class SalesToCatalog
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\Condition
 */
class SalesToCatalog
{
    /**
     * @var ConditionInterfaceFactory
     */
    private $conditionFactory;

    /**
     * @var ConditionConverter
     */
    private $conditionConverter;

    /**
     * @var array
     */
    private $conditionClassMapper = [
        CartCombine::class => CatalogRuleCombine::class,
        CartFound::class => CatalogRuleCombine::class,
        CartProduct::class => CatalogRuleProduct::class,
        CartSubselect::class => CatalogRuleCombine::class,
        CartProductCombine::class => CatalogRuleCombine::class
    ];

    /**
     * @param ConditionInterfaceFactory $conditionFactory
     * @param ConditionConverter $conditionConverter
     */
    public function __construct(
        ConditionInterfaceFactory $conditionFactory,
        ConditionConverter $conditionConverter
    ) {
        $this->conditionFactory = $conditionFactory;
        $this->conditionConverter = $conditionConverter;
    }

    /**
     * Convert SalesRule conditions to CatalogRule.
     * Output is condition model
     *
     * @param ConditionInterface $dataModel
     * @return ConditionInterface
     */
    public function convertToDataModel(ConditionInterface $dataModel)
    {
        $salesRuleData = $this->conditionConverter->dataModelToArray($dataModel);
        return $this->prepareCatalogConditionDataModel($salesRuleData);
    }

    /**
     * Convert SalesRule conditions to CatalogRule
     * Output is array with conditions
     *
     * @param ConditionInterface $dataModel
     * @return array
     */
    public function convertToArray(ConditionInterface $dataModel)
    {
        $catalogRuleModel = $this->convertToDataModel($dataModel);
        return $this->conditionConverter->dataModelToArray($catalogRuleModel);
    }

    /**
     * Prepare catalog rule data model
     *
     * @param array $salesRuleData
     * @return ConditionInterface
     */
    private function prepareCatalogConditionDataModel($salesRuleData)
    {
        /** @var ConditionInterface $conditionModel */
        $conditionModel = $this->conditionFactory->create();
        foreach ($salesRuleData as $key => $value) {
            switch ($key) {
                case 'type':
                    $value = $this->convertType($value);
                    $conditionModel->setType($value);
                    break;
                case 'attribute':
                    $value = $this->convertAttribute($salesRuleData, $value);
                    $conditionModel->setAttribute($value);
                    break;
                case 'operator':
                    $value = $this->convertOperator($salesRuleData, $value);
                    $conditionModel->setOperator($value);
                    break;
                case 'value':
                    $value = $this->convertValue($salesRuleData, $value);
                    $conditionModel->setValue($value);
                    break;
                case 'value_type':
                    $conditionModel->setValueType($value);
                    break;
                case 'aggregator':
                    $conditionModel->setAggregator($value);
                    break;
                case 'conditions':
                case 'complete':
                case 'buy_x':
                    $conditions = [];
                    /** @var array $condition */
                    foreach ($value as $condition) {
                        $conditions[] = $this->prepareCatalogConditionDataModel($condition);
                    }
                    $conditionModel->setConditions($conditions);
                    break;
                default:
            }
        }
        return $conditionModel;
    }

    /**
     * Convert condition type
     *
     * @param string $value
     * @return string
     */
    private function convertType($value)
    {
        return isset($this->conditionClassMapper[$value])
            ? $this->conditionClassMapper[$value]
            : $value;
    }

    /**
     * Convert condition attribute
     *
     * @param array $salesRuleData
     * @param string|null $value
     * @return string|null
     */
    private function convertAttribute($salesRuleData, $value)
    {
        return $salesRuleData[ConditionInterface::TYPE] == CartSubselect::class
            ? null
            : $value;
    }

    /**
     * Convert condition operator
     *
     * @param array $salesRuleData
     * @param null|$value
     * @return null|string
     */
    private function convertOperator($salesRuleData, $value)
    {
        return $this->convertAttribute($salesRuleData, $value);
    }

    /**
     * Convert condition value
     *
     * @param array $salesRuleData
     *
     * @param string|null $value
     * @return string|null
     */
    private function convertValue($salesRuleData, $value)
    {
        return $salesRuleData[ConditionInterface::TYPE] == CartSubselect::class
            ? '1'
            : $value;
    }
}
