<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Discount\Calculator;

use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Pool;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\DiscountCalculatorInterface;
use Aheadworks\Afptc\Model\Source\Rule\Discount\Type as DiscountType;

/**
 * Class PoolTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Discount\Calculator
 */
class PoolTest extends TestCase
{
    /**
     * @var Pool
     */
    private $model;

    /**
     * @var DiscountCalculatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $byPercentCaclulatorMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->byPercentCaclulatorMock = $this->getMockForAbstractClass(DiscountCalculatorInterface::class);
        $this->model = $objectManager->getObject(
            Pool::class,
            [
                'calculators' => [
                    'percent' => $this->byPercentCaclulatorMock
                ]
            ]
        );
    }

    /**
     * Test for getCalculatorByType method
     */
    public function testGetCalculatorByType()
    {
        $type = DiscountType::PERCENT;
        $this->assertSame($this->byPercentCaclulatorMock, $this->model->getCalculatorByType($type));
    }

    /**
     * Test for getCalculatorByType method on exception
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGetCalculatorByTypeOnException()
    {
        $type = 'non-existing-type';
        $this->model->getCalculatorByType($type);
    }
}
