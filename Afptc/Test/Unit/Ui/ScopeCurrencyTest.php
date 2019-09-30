<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Ui;

use Aheadworks\Afptc\Ui\ScopeCurrency;
use Magento\Directory\Model\Currency;
use Magento\Store\Model\Store;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ScopeCurrencyTest
 * @package Aheadworks\Afptc\Test\Unit\Ui
 */
class ScopeCurrencyTest extends TestCase
{
    /**
     * @var ScopeCurrency
     */
    private $component;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagerMock;

    /**
     * @var ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeConfigMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->scopeConfigMock = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $this->component = $objectManager->getObject(
            ScopeCurrency::class,
            [
                'storeManager' => $this->storeManagerMock,
                'scopeConfig' => $this->scopeConfigMock,
            ]
        );
    }

    /**
     * Test getCurrencyCode method
     *
     * @param int|null websiteId
     * @dataProvider getCurrencyCodeDataProvider
     */
    public function testGetCurrencyCode($storeIds)
    {
        $currencyCode = 'USD';
        $currencyMock = $this->createPartialMock(Currency::class, ['getCode']);
        $storeMock = $this->createPartialMock(Store::class, ['getBaseCurrency']);

        if (!empty($storeIds) && is_array($storeIds)) {
            $storeId = $storeIds[0];
            $this->storeManagerMock->expects($this->once())
                ->method('getStore')
                ->with($storeId)
                ->willReturn($storeMock);
            $storeMock->expects($this->once())
                ->method('getBaseCurrency')
                ->willReturn($currencyMock);
            $currencyMock->expects($this->once())
                ->method('getCode')
                ->willReturn($currencyCode);
        } else {
            $this->scopeConfigMock->expects($this->once())
                ->method('getValue')
                ->with(
                    Currency::XML_PATH_CURRENCY_DEFAULT,
                    ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                )->willReturn($currencyCode);
        }

        $this->assertEquals($currencyCode, $this->component->getCurrencyCode($storeIds));
    }

    /**
     * Data provider for getCurrencyCode test
     *
     * @return array
     */
    public function getCurrencyCodeDataProvider()
    {
        return [
            [[1, 2]],
            [null],
            ['string'],
            [1]
        ];
    }
}
