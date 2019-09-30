<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Cart\Converter;

use Aheadworks\Afptc\Api\Data\CartItemRuleInterface;
use Aheadworks\Afptc\Api\Data\CartItemRuleInterfaceFactory;
use Aheadworks\Afptc\Model\Cart\Converter\InfoBuyRequest;
use Magento\Framework\Api\DataObjectHelper;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class InfoBuyRequestTest
 * @package Aheadworks\Afptc\Test\Unit\Model\Cart\Converter
 */
class InfoBuyRequestTest extends TestCase
{
    /**
     * @var InfoBuyRequest
     */
    private $model;

    /**
     * @var DataObjectHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataObjectHelperMock;

    /**
     * @var CartItemRuleInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartItemRuleFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->dataObjectHelperMock = $this->createPartialMock(DataObjectHelper::class, ['populateWithArray']);
        $this->cartItemRuleFactoryMock = $this->createPartialMock(CartItemRuleInterfaceFactory::class, ['create']);

        $this->model = $objectManager->getObject(
            InfoBuyRequest::class,
            [
                'dataObjectHelper' => $this->dataObjectHelperMock,
                'cartItemRuleFactory' => $this->cartItemRuleFactoryMock
            ]
        );
    }

    /**
     * Test convertToDataModel method
     * @param array $dataArray
     * @dataProvider convertToDataModelDataProvider
     */
    public function testConvertToDataModel($dataArray)
    {
        $cartItemRuleMock = $this->getMockForAbstractClass(CartItemRuleInterface::class);
        if ($dataArray === false) {
            $dataArray = [$cartItemRuleMock];
        } else {
            $this->cartItemRuleFactoryMock->expects($this->once())
                ->method('create')
                ->willReturn($cartItemRuleMock);
            $this->dataObjectHelperMock->expects($this->once())
                ->method('populateWithArray')
                ->with($cartItemRuleMock, $dataArray[0], CartItemRuleInterface::class)
                ->willReturn($cartItemRuleMock);
        }

        $this->assertEquals([$cartItemRuleMock], $this->model->convertToDataModel($dataArray));
    }

    /**
     * Data provider for convertToDataModel test
     *
     * @return array
     */
    public function convertToDataModelDataProvider()
    {
        return [
            [
                [['rule_id' => 1, 'qty' => 1]],
                false
            ]
        ];
    }
}
