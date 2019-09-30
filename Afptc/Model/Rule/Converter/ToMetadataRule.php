<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterfaceFactory;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor\Pool;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class ToMetadataRule
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter
 */
class ToMetadataRule
{
    /**
     * @var Pool
     */
    private $processorPool;

    /**
     * @var RuleMetadataInterfaceFactory
     */
    private $ruleMetadataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param Pool $processorPool
     * @param RuleMetadataInterfaceFactory $ruleMetadataFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        Pool $processorPool,
        RuleMetadataInterfaceFactory $ruleMetadataFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->processorPool = $processorPool;
        $this->ruleMetadataFactory = $ruleMetadataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Convert rule data to metadata
     *
     * @param RuleInterface[] $rules
     * @param AbstractItem[] $items
     * @param string $processorType
     * @return RuleMetadataInterface[]
     * @throws \Exception
     */
    public function convert($rules, $items, $processorType)
    {
        $metadataRules = [];
        $this->addUniqueKeyToItems($items);
        foreach ($rules as $rule) {
            /** @var RuleMetadataInterface $ruleMetadataObject */
            $ruleMetadataObject = $this->ruleMetadataFactory->create();
            $ruleMetadataObject->setRule($rule);

            $processor = $this->processorPool->getProcessor($processorType);
            $processor->prepareData($ruleMetadataObject, $items, $metadataRules);
            if ($ruleMetadataObject->getPromoProducts()) {
                $metadataRules[] = $ruleMetadataObject;
            }
        }

        return $metadataRules;
    }

    /**
     * Add unique key to items for correct calculated discount
     *
     * @param AbstractItem[] $items
     * @return void
     */
    private function addUniqueKeyToItems($items)
    {
        foreach ($items as $item) {
            $item->setAwAfptcId(uniqid());
            if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                foreach ($item->getChildren() as $child) {
                    $child->setAwAfptcId(uniqid());
                }
            }
        }
    }
}
