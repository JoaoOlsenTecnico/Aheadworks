<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Test\Unit\Model\Rule\Promo\Block\Layout\Processor;

use Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor\Common;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Afptc\Api\Data\PromoInfoBlockInterface;
use Aheadworks\Afptc\Model\Source\Rule\Promo\Renderer\Placement;
use Aheadworks\Afptc\Api\Data\PromoInterface;

/**
 * Class CommonTest
 *
 * @package Aheadworks\Afptc\Test\Unit\Model\Rule\Promo\Block\Layout\Processor
 */
class CommonTest extends TestCase
{
    /**
     * @var Common
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
        $this->model = $objectManager->getObject(Common::class, []);
    }

    /**
     * Test for process method
     */
    public function testProcess()
    {
        $promoInfoBlockMock = $this->getMockForAbstractClass(PromoInfoBlockInterface::class);
        $promo = $this->getMockForAbstractClass(PromoInterface::class);
        $promoInfoBlockMock->expects($this->once())
            ->method('getPromo')
            ->willReturn($promo);
        $promo->expects($this->once())
            ->method('getPromoOfferInfoText')
            ->willReturn('someText');
        $promo->expects($this->once())
            ->method('getPromoHeader')
            ->willReturn('someHeader');
        $promo->expects($this->once())
            ->method('getPromoDescription')
            ->willReturn('somePromoDescription');

        $this->model->process([], $promoInfoBlockMock, Placement::PRODUCT_PAGE, 'scope1');
    }
}
