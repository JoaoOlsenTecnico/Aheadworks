<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Indexer\Rule;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Aheadworks\Afptc\Model\Indexer\Rule\Action\DataChecker\Comparator;
use Aheadworks\Afptc\Model\Indexer\Rule\Action\DataChecker\ExcludeDataFilter;

/**
 * Class DataChecker
 *
 * @package Aheadworks\Afptc\Model\Indexer\Rule
 */
class DataChecker
{
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var Comparator
     */
    private $comparator;

    /**
     * @var ExcludeDataFilter
     */
    private $excludeFilter;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param Comparator $comparator
     * @param ExcludeDataFilter $excludeFilter
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        Comparator $comparator,
        ExcludeDataFilter $excludeFilter
    ) {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->comparator = $comparator;
        $this->excludeFilter = $excludeFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function isChanged($newRuleModel, $oldRuleModel)
    {
        $newData = $this->dataObjectProcessor->buildOutputDataArray($newRuleModel, RuleInterface::class);
        $oldData = $this->dataObjectProcessor->buildOutputDataArray($oldRuleModel, RuleInterface::class);
        $arrayDiff = $this->comparator->findDiffInArrays($newData, $oldData);

        return !empty($this->excludeFilter->apply($arrayDiff));
    }
}
