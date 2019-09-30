<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\Scenario;

use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\ScenarioInterface;

/**
 * Class Coupon
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\Scenario
 */
class Coupon implements ScenarioInterface
{
    /**
     * {@inheritdoc}
     */
    public function getQtyToDiscountByRule($rule, $items)
    {
        return 1;
    }
}
