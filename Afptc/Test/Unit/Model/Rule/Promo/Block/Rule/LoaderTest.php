<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Promo\Block\Rule;

use Magento\Catalog\Api\Data\ProductInterface;
use Aheadworks\Afptc\Api\Data\PromoInterface;
use Aheadworks\Afptc\Api\Data\PromoInterfaceFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResource;
use Magento\Framework\Api\DataObjectHelper;
use Aheadworks\Afptc\Model\Rule\Promo\Block\Rule\Loader;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class LoaderTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Promo\Block\Rule
 */
class LoaderTest extends TestCase
{
    /**
     * @var Loader
     */
    private $model;

    /**
     * @var RuleResource|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleResourceMock;

    /**
     * @var DateTime|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dateTimeMock;

    /**
     * @var PromoInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $promoFactoryMock;

    /**
     * @var DataObjectHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataObjectHelperMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->ruleResourceMock = $this->createPartialMock(RuleResource::class, ['getSortedRuleDataForProduct']);
        $this->dateTimeMock = $this->createPartialMock(DateTime::class, ['gmtDate']);
        $this->promoFactoryMock = $this->createPartialMock(PromoInterfaceFactory::class, ['create']);
        $this->dataObjectHelperMock = $this->createPartialMock(DataObjectHelper::class, ['populateWithArray']);
        $this->model = $objectManager->getObject(
            Loader::class,
            [
                'ruleResource' => $this->ruleResourceMock,
                'dateTime' => $this->dateTimeMock,
                'promoFactory' => $this->promoFactoryMock,
                'dataObjectHelper' => $this->dataObjectHelperMock
            ]
        );
    }

    /**
     * Test for getAvailableRuleDataForProduct
     */
    public function testGetAvailableRuleDataForProduct()
    {
        $productMock = $this->createPartialMock(Product::class, ['getId', 'getStoreId']);
        $customerGroupId = 4;
        $productId = 1;
        $storeId = 1;
        $ruleData = ['someData'];
        $currentDate = '12-12-2012';
        $productMock->expects($this->once())
            ->method('getId')
            ->willReturn($productId);
        $productMock->expects($this->once())
            ->method('getStoreId')
            ->willReturn($storeId);
        $this->dateTimeMock->expects($this->once())
            ->method('gmtDate')
            ->with(StdlibDateTime::DATE_PHP_FORMAT)
            ->willReturn($currentDate);
        $this->ruleResourceMock->expects($this->once())
            ->method('getSortedRuleDataForProduct')
            ->with($productId, $customerGroupId, $storeId, $currentDate)
            ->willReturn($ruleData);

        $this->assertSame($ruleData, $this->model->getAvailableRuleDataForProduct($productMock, $customerGroupId));
    }

    /**
     * Test for getPromoItems
     */
    public function testGetPromoItems()
    {
        $someRuleData = ['someData'];
        $promoItemMock = $this->getMockForAbstractClass(PromoInterface::class);
        $this->promoFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($promoItemMock);
        $this->dataObjectHelperMock->expects($this->any())
            ->method('populateWithArray')
            ->with($promoItemMock, $someRuleData, PromoInterface::class);

        $this->assertEquals([$promoItemMock], $this->model->getPromoItems([$someRuleData]));
    }
}
