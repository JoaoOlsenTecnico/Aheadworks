<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor;

use Aheadworks\Afptc\Api\Data\PromoInfoBlockInterface;

/**
 * Interface LayoutProcessorInterface
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor
 */
interface LayoutProcessorInterface
{
    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @param PromoInfoBlockInterface $promoInfoBlock
     * @param string $placement
     * @param string $scope
     * @return array
     */
    public function process($jsLayout, $promoInfoBlock, $placement, $scope);
}
