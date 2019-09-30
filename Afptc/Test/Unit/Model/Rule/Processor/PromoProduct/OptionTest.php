<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Processor\PromoProduct;

use Magento\Quote\Api\Data\ProductOptionInterfaceFactory;
use Magento\Quote\Api\Data\ProductOptionInterface;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Serialize\Serializer\Json;
use Aheadworks\Afptc\Model\Rule\Processor\PromoProduct\Option;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class OptionTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Processor\PromoProduct.
 */
class OptionTest extends TestCase
{
    /**
     * @var Option
     */
    private $model;

    /**
     * @var DataObjectProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataObjectProcessorMock;

    /**
     * @var DataObjectHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataObjectHelperMock;

    /**
     * @var ProductOptionInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productOptionFactoryMock;

    /**
     * @var Json|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serializerMock;

    /**
     * @var array
     */
    private $optionArray = [
        'option1' => 'opt',
        'value1' => 'val'
    ];

    /**
     * @var string
     */
    private $serializedOptions = ['opt1:opt,value1:val'];

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->dataObjectProcessorMock = $this->createPartialMock(
            DataObjectProcessor::class,
            ['buildOutputDataArray']
        );
        $this->dataObjectHelperMock = $this->createPartialMock(
            DataObjectHelper::class,
            ['populateWithArray']
        );
        $this->productOptionFactoryMock = $this->createPartialMock(
            ProductOptionInterfaceFactory::class,
            ['create']
        );
        $this->serializerMock = $this->createPartialMock(
            Json::class,
            ['serialize', 'unserialize']
        );

        $this->model = $objectManager->getObject(
            Option::class,
            [
                'dataObjectProcessor' => $this->dataObjectProcessorMock,
                'dataObjectHelper' => $this->dataObjectHelperMock,
                'productOptionFactory' => $this->productOptionFactoryMock,
                'serializer' => $this->serializerMock
            ]
        );
    }

    /**
     * Test for beforeSave method
     */
    public function testBeforeSave()
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        list($promoProductMock1, $promoProductMock2) = $this->preparePromoProducts();

        $option1 = $this->getMockForAbstractClass(ProductOptionInterface::class);
        $option2 = null;

        $promoProductMock1->expects($this->any())
            ->method('getOption')
            ->willReturn($option1);
        $promoProductMock2->expects($this->any())
            ->method('getOption')
            ->willReturn($option2);

        $ruleMock->expects($this->once())
            ->method('getPromoProducts')
            ->willReturn([$promoProductMock1, $promoProductMock2]);

        $this->dataObjectProcessorMock->expects($this->once())
            ->method('buildOutputDataArray')
            ->willReturn($this->optionArray);
        $this->serializerMock->expects($this->once())
            ->method('serialize')
            ->with($this->optionArray)
            ->willReturn($this->serializedOptions);
        $promoProductMock1->expects($this->once())
            ->method('setOption')
            ->willReturn($this->serializedOptions);

        $this->assertSame($ruleMock, $this->model->beforeSave($ruleMock));
    }

    /**
     * Test for afterLoad method
     */
    public function testAfterLoad()
    {
        $ruleMock = $this->getMockForAbstractClass(RuleInterface::class);
        list($promoProductMock1) = $this->preparePromoProducts();
        $promoProductMock2 = ['sku' => 'test', 'option' => $this->serializedOptions];

        $promoProductMock1->expects($this->any())
            ->method('getOption')
            ->willReturn($this->serializedOptions);
        $this->serializerMock->expects($this->exactly(2))
            ->method('unserialize')
            ->with($this->serializedOptions)
            ->willReturn($this->optionArray);

        $optionObject = $this->getMockForAbstractClass(ProductOptionInterface::class);
        $this->productOptionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($optionObject);
        $this->dataObjectHelperMock->expects($this->any())
            ->method('populateWithArray')
            ->with($optionObject, $this->optionArray, ProductOptionInterface::class);

        $ruleMock->expects($this->once())
            ->method('getPromoProducts')
            ->willReturn([$promoProductMock1, $promoProductMock2]);

        $ruleMock->expects($this->once())
            ->method('setPromoProducts');

        $this->assertSame($ruleMock, $this->model->afterLoad($ruleMock));
    }

    /**
     * Prepare promo products
     *
     * @return array
     */
    private function preparePromoProducts()
    {
        $promoProductMock1 = $this->getMockForAbstractClass(RulePromoProductInterface::class);
        $promoProductMock2 = $this->getMockForAbstractClass(RulePromoProductInterface::class);

        return [$promoProductMock1, $promoProductMock2];
    }
}
