<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer\Quote\Address\Processor;

use Magento\Quote\Model\Quote\Address;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor\CartAttribute;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor\CartAttribute\Mapper;

/**
 * Class CartAttributeTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer\Quote\Address\Processor
 */
class CartAttributeTest extends TestCase
{
    /**
     * @var CartAttribute
     */
    private $model;

    /**
     * @var Mapper
     */
    private $mapperMock;

    /**
     * @var array
     */
    private $attributes;

    /**#@+
     * Constants defined for testing
     */
    const ADDRESS_ATTRIBUTE_CODE = 'addressAttr1';
    const ADDRESS_ATTRIBUTE_VALUE = 50;
    const ITEM_ATTRIBUTE_CODE = 'itemAttr1';
    const ITEM_ATTRIBUTE_VALUE = 20;
    /**#@-*/

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->attributes = [self::ADDRESS_ATTRIBUTE_CODE => self::ITEM_ATTRIBUTE_CODE];
        $this->mapperMock = $this->createMock(Mapper::class);
        $this->model = $objectManager->getObject(
            CartAttribute::class,
            [
                'attributeMapper' => $this->mapperMock,
                'attributes' => $this->attributes
            ]
        );
    }

    /**
     * Test for process method
     */
    public function testProcess()
    {
        $addressMock = $this->createPartialMock(
            Address::class,
            [
                'getData',
                'getAllVisibleItems',
            ]
        );
        $itemMock = $this->createPartialMock(
            AbstractItem::class,
            ['getQuote', 'getAddress', 'getOptionByCode', 'getAwAfptcIsPromo', 'getData']
        );

        $itemMock->expects($this->once())
            ->method('getAwAfptcIsPromo')
            ->willReturn(true);

        $addressMock->expects($this->once())
            ->method('getData')
            ->with('addressAttr1')
            ->willReturn(self::ADDRESS_ATTRIBUTE_VALUE);
        $this->mapperMock->expects($this->once())
            ->method('mapAttribute')
            ->with(self::ITEM_ATTRIBUTE_CODE)
            ->willReturn(self::ITEM_ATTRIBUTE_CODE);
        $itemMock->expects($this->once())
            ->method('getData')
            ->with('itemAttr1')
            ->willReturn(self::ITEM_ATTRIBUTE_VALUE);

        $addressMock->expects($this->once())
            ->method('getAllVisibleItems')
            ->willReturn([$itemMock]);

        $result = ['addressAttr1' => self::ADDRESS_ATTRIBUTE_VALUE - self::ITEM_ATTRIBUTE_VALUE];
        $this->assertSame($result, $this->model->process($addressMock, []));
    }
}
