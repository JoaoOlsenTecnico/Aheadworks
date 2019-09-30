<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model;

use Aheadworks\Afptc\Api\RuleRepositoryInterface;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleInterfaceFactory;
use Aheadworks\Afptc\Api\Data\RuleSearchResultsInterface;
use Aheadworks\Afptc\Api\Data\RuleSearchResultsInterfaceFactory;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResourceModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class RuleRepository
 *
 * @package Aheadworks\Afptc\Model
 */
class RuleRepository implements RuleRepositoryInterface
{
    /**
     * @var RuleResourceModel
     */
    private $resource;

    /**
     * @var RuleInterfaceFactory
     */
    private $ruleInterfaceFactory;

    /**
     * @var RuleCollectionFactory
     */
    private $ruleCollectionFactory;

    /**
     * @var RuleSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var array
     */
    private $registry = [];

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param RuleResourceModel $resource
     * @param RuleInterfaceFactory $ruleInterfaceFactory
     * @param RuleCollectionFactory $ruleCollectionFactory
     * @param RuleSearchResultsInterfaceFactory $searchResultsFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RuleResourceModel $resource,
        RuleInterfaceFactory $ruleInterfaceFactory,
        RuleCollectionFactory $ruleCollectionFactory,
        RuleSearchResultsInterfaceFactory $searchResultsFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        CollectionProcessorInterface $collectionProcessor,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->ruleInterfaceFactory = $ruleInterfaceFactory;
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(RuleInterface $rule)
    {
        try {
            $this->resource->save($rule);
            $this->registry[$rule->getRuleId()] = $rule;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $rule;
    }

    /**
     * {@inheritdoc}
     */
    public function get($ruleId, $storeId = null)
    {
        $storeId = $storeId ? : $this->storeManager->getStore()->getId();
        $cacheKey = implode('-', [$ruleId, $storeId]);
        if (!isset($this->registry[$cacheKey])) {
            /** @var RuleInterface $rule */
            $rule = $this->ruleInterfaceFactory->create();
            $rule->setStoreId($storeId);
            $this->resource->load($rule, $ruleId);
            if (!$rule->getRuleId()) {
                throw NoSuchEntityException::singleField('rule_id', $ruleId);
            }
            $this->registry[$cacheKey] = $rule;
        }
        return $this->registry[$cacheKey];
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria, $storeId = null)
    {
        /** @var \Aheadworks\Afptc\Model\ResourceModel\Rule\Collection $collection */
        $collection = $this->ruleCollectionFactory->create();
        $storeId = $storeId ? : $this->storeManager->getStore()->getId();
        $collection->setStoreId($storeId);
        $collection->addAttributeToSelect('*');

        $this->extensionAttributesJoinProcessor->process($collection, RuleInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var RuleSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());

        $objects = [];
        /** @var Rule $item */
        foreach ($collection->getItems() as $item) {
            $objects[] = $this->getDataObject($item);
        }
        $searchResults->setItems($objects);

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(RuleInterface $rule)
    {
        try {
            $this->resource->delete($rule);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        if (isset($this->registry[$rule->getId()])) {
            unset($this->registry[$rule->getId()]);
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($ruleId)
    {
        return $this->delete($this->get($ruleId));
    }

    /**
     * Retrieves data object using model
     *
     * @param Rule $model
     * @return RuleInterface
     */
    private function getDataObject($model)
    {
        /** @var RuleInterface $object */
        $object = $this->ruleInterfaceFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $object,
            $this->dataObjectProcessor->buildOutputDataArray($model, RuleInterface::class),
            RuleInterface::class
        );
        return $object;
    }
}
