<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor;

use Aheadworks\Afptc\Model\Rule\Converter\Condition as ConditionConverter;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\CompleteFactory;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class CartCondition
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor
 */
class CartCondition implements ProcessorInterface
{
    /**
     * @var ConditionConverter
     */
    private $conditionConverter;

    /**
     * @var CompleteFactory
     */
    private $cartRuleFactory;

    /**
     * @var array
     */
    private $scenarioConditionMap;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param ConditionConverter $conditionConverter
     * @param CompleteFactory $cartRuleFactory
     * @param Json $serializer
     * @param array $scenarioConditionMap
     */
    public function __construct(
        ConditionConverter $conditionConverter,
        CompleteFactory $cartRuleFactory,
        Json $serializer,
        array $scenarioConditionMap
    ) {
        $this->conditionConverter = $conditionConverter;
        $this->cartRuleFactory = $cartRuleFactory;
        $this->serializer = $serializer;
        $this->scenarioConditionMap = $scenarioConditionMap;
    }

    /**
     * Prepare product conditions for save
     *
     * @param array $data
     * @return array
     */
    public function process($data)
    {
        $conditionType = $this->scenarioConditionMap[$data[RuleInterface::SCENARIO]];
        $data[RuleInterface::CART_CONDITIONS] = $this->prepareCartConditionData($data, $conditionType);
        unset($data['rule']);
        return $data;
    }

    /**
     * Prepare cart condition data
     *
     * @param array $data
     * @param string $conditionType
     * @return array
     */
    private function prepareCartConditionData(array $data, $conditionType)
    {
        $cartConditionArray = [];
        if (isset($data['rule'][$conditionType])) {
            $conditionArray = $this->convertFlatToRecursive($data['rule'], [$conditionType]);
            if (is_array($conditionArray[$conditionType]['1'])) {
                $cartConditionArray = $conditionArray[$conditionType]['1'];
            }
        } else {
            if (isset($data[RuleInterface::CART_CONDITIONS])) {
                $cartConditionArray = $this->serializer->unserialize($data[RuleInterface::CART_CONDITIONS]);
            } else {
                $cartRule = $this->cartRuleFactory->create();
                $defaultConditions = [];
                $defaultConditions['rule'] = [];
                $defaultConditions['rule'][$conditionType] = $cartRule
                    ->setConditions([])
                    ->getConditions()
                    ->asArray();
                $cartConditionArray = $this->convertFlatToRecursive($defaultConditions, [$conditionType]);
            }
        }

        $dataModel = $this->conditionConverter->arrayToDataModel($cartConditionArray);
        return $this->conditionConverter->dataModelToArray($dataModel);
    }

    /**
     * Get conditions data recursively
     *
     * @param array $data
     * @param array $allowedKeys
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function convertFlatToRecursive(array $data, $allowedKeys = [])
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedKeys) && is_array($value)) {
                foreach ($value as $id => $data) {
                    $path = explode('--', $id);
                    $node = & $result;

                    for ($i = 0, $l = sizeof($path); $i < $l; $i++) {
                        if (!isset($node[$key][$path[$i]])) {
                            $node[$key][$path[$i]] = [];
                        }
                        $node = & $node[$key][$path[$i]];
                    }
                    foreach ($data as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $dk => $dv) {
                                if (empty($dv)) {
                                    unset($v[$dk]);
                                }
                            }
                            if (!count($v)) {
                                continue;
                            }
                        }

                        $node[$k] = $v;
                    }
                }
            }
        }

        return $result;
    }
}
