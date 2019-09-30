<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleInterfaceFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProvider as RuleFormDataProvider;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Aheadworks\Afptc\Controller\Adminhtml\Rule\Edit as RuleEditAction;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class DataProvider
 *
 * @package Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions
 */
class DataProvider
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var RuleInterfaceFactory
     */
    private $ruleFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var TypeResolver
     */
    private $typeResolver;

    /**
     * @param DataPersistorInterface $dataPersistor
     * @param DataObjectProcessor $dataObjectProcessor
     * @param Registry $coreRegistry
     * @param RuleInterfaceFactory $ruleFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param TypeResolver $typeResolver
     */
    public function __construct(
        DataPersistorInterface $dataPersistor,
        DataObjectProcessor $dataObjectProcessor,
        Registry $coreRegistry,
        RuleInterfaceFactory $ruleFactory,
        DataObjectHelper $dataObjectHelper,
        TypeResolver $typeResolver
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->coreRegistry = $coreRegistry;
        $this->ruleFactory = $ruleFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->typeResolver = $typeResolver;
    }

    /**
     * Retrieve condition data array from the rule
     *
     * @param $conditionType
     * @return array|null
     */
    public function getConditions($conditionType)
    {
        $conditions = null;
        $rule = $this->getRule();
        if ($rule
            && $rule->getCartConditions()
            && in_array($rule->getScenario(), $this->typeResolver->resolve($conditionType))
        ) {
            $ruleData = $this->dataObjectProcessor->buildOutputDataArray(
                $rule,
                RuleInterface::class
            );
            $conditions = $ruleData[RuleInterface::CART_CONDITIONS];
        }
        return $conditions;
    }

    /**
     * Retrieve rule model
     *
     * @return RuleInterface|null
     */
    public function getRule()
    {
        $persistedRule = $this->dataPersistor->get(RuleFormDataProvider::DATA_PERSISTOR_FORM_DATA_KEY);
        if (!empty($persistedRule && is_array($persistedRule))) {
            $rule = $this->ruleFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $rule,
                $persistedRule,
                RuleInterface::class
            );
        } else {
            /** @var RuleInterface $rule */
            $rule = $this->coreRegistry->registry(RuleEditAction::KEY_RULE_DATA);
        }

        return $rule;
    }
}
