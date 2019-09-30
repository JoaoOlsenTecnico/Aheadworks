<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\RuleRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\Afptc\Model\Indexer\Rule\ProductLoader;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct\DataProcessor\Rule;

/**
 * Class DataProcessor
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct
 */
class DataProcessor
{
    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var DataCollector;
     */
    private $dataCollector;

    /**
     * @var ProductLoader
     */
    private $productLoader;

    /**
     * @var Rule
     */
    private $ruleProcessor;

    /**
     * @param RuleRepositoryInterface $ruleRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DataCollector $dataCollector
     * @param ProductLoader $productLoader
     * @param Rule $ruleProcessor
     */
    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataCollector $dataCollector,
        ProductLoader $productLoader,
        Rule $ruleProcessor
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->dataCollector = $dataCollector;
        $this->productLoader = $productLoader;
        $this->ruleProcessor = $ruleProcessor;
    }

    /**
     * Prepare and return data for insert to index table
     *
     * @return array
     * @throws LocalizedException
     * @throws \Exception
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataToInsert()
    {
        $rules = $this->ruleProcessor->checkAllRuleData($this->getActiveRules());
        $result = [];
        foreach ($rules as $rule) {
            $data = $this->dataCollector->prepareRuleData($rule);
            $result = array_merge($result, $data);
        }
        return $result;
    }

    /**
     * Prepare and return data for update to index table
     *
     * @param array $ids
     * @return array
     * @throws LocalizedException
     * @throws \Exception
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataToUpdate($ids)
    {
        $affectedRulesIds = $this->ruleProcessor->checkRuleDataForProducts($ids);
        $rules = $this->getActiveRules();
        $products = $this->productLoader->getProducts($ids);

        $result = [];
        $result[Rule::AFFECTED_RULE_IDS] = $affectedRulesIds;
        foreach ($rules as $rule) {
            foreach ($products as $product) {
                $data = $this->dataCollector->prepareRuleProductData($rule, $product);
                $result = array_merge($result, $data);
            }
        }
        return $result;
    }

    /**
     * @return RuleInterface[]
     * @throws LocalizedException
     */
    private function getActiveRules()
    {
        $this->searchCriteriaBuilder->addFilter(RuleInterface::IS_ACTIVE, ['eq' => true]);
        return $this->ruleRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();
    }
}
