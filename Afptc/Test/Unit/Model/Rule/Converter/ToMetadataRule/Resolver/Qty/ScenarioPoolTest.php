<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Converter\ToMetadataRule\Resolver\Qty;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\ScenarioPool;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\ScenarioInterface;

/**
 * Class ScenarioPoolTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Converter\ToMetadataRule\Resolver\Qty
 */
class ScenarioPoolTest extends TestCase
{
    /**
     * @var ScenarioPool
     */
    private $model;

    /**
     * @var ScenarioInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $scenarioMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->scenarioMock = $this->getMockForAbstractClass(ScenarioInterface::class);
        $this->model = $objectManager->getObject(
            ScenarioPool::class,
            [
                'scenarios' => [
                    'coupon' => $this->scenarioMock
                ]
            ]
        );
    }

    /**
     * Test for getStockProduct method
     */
    public function testGetScenarioProcessor()
    {
        $type = 'coupon';
        $this->assertSame($this->scenarioMock, $this->model->getScenarioProcessor($type));
    }

    /**
     * Test for getScenarioProcessor method with non-existing type
     *
     * @expectedException \Exception
     */
    public function testGetStockProductWithNonExistingType()
    {
        $type = 'non-existing-type';
        $this->model->getScenarioProcessor($type);
    }
}
