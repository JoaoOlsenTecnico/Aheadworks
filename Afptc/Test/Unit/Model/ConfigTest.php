<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model;

use Aheadworks\Afptc\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class ConfigTest
 * @package Aheadworks\Afptc\Test\Unit\Model
 */
class ConfigTest extends TestCase
{
    /**
     * @var Config
     */
    private $model;

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
        $this->scopeConfigMock = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $this->model = $objectManager->getObject(
            Config::class,
            [
                'scopeConfig' => $this->scopeConfigMock
            ]
        );
    }

    /**
     * Test getDefaultOfferPopupTitle method
     */
    public function testGetDefaultOfferPopupTitle()
    {
        $expected = 'title';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_GENERAL_DEFAULT_OFFER_POPUP_TITLE)
            ->willReturn($expected);

        $this->assertEquals($expected, $this->model->getDefaultOfferPopupTitle());
    }

    /**
     * Test isOptionBlockHidden method
     */
    public function testIsOptionBlockHidden()
    {
        $expected = true;

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_GENERAL_IS_OPTION_BLOCK_HIDDEN)
            ->willReturn($expected);

        $this->assertEquals($expected, $this->model->isOptionBlockHidden());
    }

    /**
     * Test getWhereToDisplayPopupType method
     */
    public function testGetWhereToDisplayPopupType()
    {
        $expected = 'checkout';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_GENERAL_WHERE_TO_DISPLAY_POPUP_TYPE)
            ->willReturn($expected);

        $this->assertEquals($expected, $this->model->getWhereToDisplayPopupType());
    }

    /**
     * Test getSubtotalValidationType method
     */
    public function testGetSubtotalValidationType()
    {
        $expected = '1';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_GENERAL_SUBTOTAL_VALIDATION_TYPE)
            ->willReturn($expected);

        $this->assertEquals($expected, $this->model->getSubtotalValidationType());
    }
}
