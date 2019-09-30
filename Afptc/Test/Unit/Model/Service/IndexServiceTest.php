<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Service;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\Service\IndexService;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Indexer\Rule\Processor as RuleProcessor;
use Psr\Log\LoggerInterface;
use Aheadworks\Afptc\Model\Indexer\Rule\DataChecker;

/**
 * Class IndexServiceTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Service
 */
class IndexServiceTest extends TestCase
{
    /**
     * @var IndexService
     */
    private $model;

    /**
     * @var RuleProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleProcessorMock;

    /**
     * @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loggerMock;

    /**
     * @var DataChecker|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataCheckerMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->ruleProcessorMock = $this->createPartialMock(RuleProcessor::class, ['markIndexerAsInvalid']);
        $this->loggerMock = $this->getMockForAbstractClass(LoggerInterface::class);
        $this->dataCheckerMock = $this->createPartialMock(DataChecker::class, ['isChanged']);
        $this->model = $objectManager->getObject(
            IndexService::class,
            [
                'ruleProcessor' => $this->ruleProcessorMock,
                'logger' => $this->loggerMock,
                'dataChecker' => $this->dataCheckerMock
            ]
        );
    }

    /**
     * Test invalidateIndexOnDataChange method
     *
     * @param bool $isChanged
     * @dataProvider invalidateIndexOnDataChangeDataProvider
     */
    public function testInvalidateIndexOnDataChange($isChanged)
    {
        $newRuleModelMock = $this->getMockForAbstractClass(RuleInterface::class);
        $oldRuleModelMock = $this->getMockForAbstractClass(RuleInterface::class);

        $this->dataCheckerMock->expects($this->once())
            ->method('isChanged')
            ->with($newRuleModelMock, $oldRuleModelMock)
            ->willReturn($isChanged);

        if ($isChanged) {
            $this->ruleProcessorMock->expects($this->once())
                ->method('markIndexerAsInvalid');
        }

        $this->model->invalidateIndexOnDataChange($newRuleModelMock, $oldRuleModelMock);
    }

    /**
     * Data provider for invalidateIndexOnDataChange test
     *
     * @return array
     */
    public function invalidateIndexOnDataChangeDataProvider()
    {
        return [
            [true],
            [false]
        ];
    }

    /**
     * Test invalidateIndexOnDataChange method on exception
     */
    public function testInvalidateIndexOnDataChangeOnException()
    {
        $newRuleModelMock = $this->getMockForAbstractClass(RuleInterface::class);
        $oldRuleModelMock = $this->getMockForAbstractClass(RuleInterface::class);
        $exception = new \Exception('exception');

        $this->dataCheckerMock->expects($this->once())
            ->method('isChanged')
            ->with($newRuleModelMock, $oldRuleModelMock)
            ->willReturn(true);

        $this->ruleProcessorMock->expects($this->once())
            ->method('markIndexerAsInvalid')
            ->willThrowException($exception);

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with($exception);

        $this->model->invalidateIndexOnDataChange($newRuleModelMock, $oldRuleModelMock);
    }
}
