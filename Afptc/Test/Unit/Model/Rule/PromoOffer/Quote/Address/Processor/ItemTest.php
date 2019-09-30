<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer\Quote\Address\Processor;

use Magento\Quote\Model\Quote\Address;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor\Item;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class ItemTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\PromoOffer\Quote\Address\Processor
 */
class ItemTest extends TestCase
{
    /**
     * @var Item
     */
    private $model;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(Item::class, []);
    }

    /**
     * Test for process method
     */
    public function testProcess()
    {
        $addressMock = $this->createPartialMock(Address::class, ['getAllItems']);
        $parentId = 5;

        $itemMock1 = $this->generateItem(true, true, $parentId, null);
        $itemMock2 = $this->generateItem(false, false, 6, $parentId);
        $itemMock3 = $this->generateItem(false, false, 12, null);

        $addressMock->expects($this->once())
            ->method('getAllItems')
            ->willReturn([$itemMock1, $itemMock2, $itemMock3]);

        $result = ['cached_items_all' => [ '2' => $itemMock3]];
        $this->assertEquals($result, $this->model->process($addressMock, []));
    }

    /**
     * Generate abstract item
     *
     * @param bool $isPromo
     * @param bool $hasChildren
     * @param int $itemId
     * @param int|null $parentId
     * @return AbstractItem|\PHPUnit_Framework_MockObject_MockObject
     */
    private function generateItem($isPromo, $hasChildren, $itemId, $parentId)
    {
        $itemMock = $this->createPartialMock(
            AbstractItem::class,
            [
                'getAwAfptcIsPromo',
                'getHasChildren',
                'getId',
                'getParentItemId',
                'getOptionByCode',
                'getAddress',
                'getQuote'
            ]
        );
        $itemMock->expects($this->any())
            ->method('getAwAfptcIsPromo')
            ->willReturn($isPromo);
        $itemMock->expects($this->any())
            ->method('getHasChildren')
            ->willReturn($hasChildren);
        $itemMock->expects($this->any())
            ->method('getId')
            ->willReturn($itemId);
        $itemMock->expects($this->any())
            ->method('getParentItemId')
            ->willReturn($parentId);

        return $itemMock;
    }
}
