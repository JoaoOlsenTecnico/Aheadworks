<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Service;

use Aheadworks\Afptc\Api\IndexManagementInterface;
use Aheadworks\Afptc\Model\Indexer\Rule\Processor as RuleProcessor;
use Psr\Log\LoggerInterface;
use Aheadworks\Afptc\Model\Indexer\Rule\DataChecker;

/**
 * Class IndexService
 *
 * @package Aheadworks\Afptc\Model\Service
 */
class IndexService implements IndexManagementInterface
{
    /**
     * @var RuleProcessor
     */
    private $ruleProcessor;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DataChecker
     */
    private $dataChecker;

    /**
     * @param RuleProcessor $ruleProcessor
     * @param LoggerInterface $logger
     * @param DataChecker $dataChecker
     */
    public function __construct(
        RuleProcessor $ruleProcessor,
        LoggerInterface $logger,
        DataChecker $dataChecker
    ) {
        $this->ruleProcessor = $ruleProcessor;
        $this->logger = $logger;
        $this->dataChecker = $dataChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function invalidateIndexOnDataChange($newRuleModel, $oldRuleModel)
    {
        if ($this->dataChecker->isChanged($newRuleModel, $oldRuleModel)) {
            $this->invalidateIndex();
        }
    }

    /**
     * Invalidate rule index
     *
     * @return bool
     */
    private function invalidateIndex()
    {
        try {
            $this->ruleProcessor->markIndexerAsInvalid();
            $result = true;
        } catch (\Exception $e) {
            $result = false;
            $this->logger->error($e);
        }

        return $result;
    }
}
