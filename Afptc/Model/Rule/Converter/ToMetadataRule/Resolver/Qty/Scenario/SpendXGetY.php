<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\Scenario;

use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\ScenarioInterface;

/**
 * Class SpendXGetY
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\Scenario
 */
class SpendXGetY implements ScenarioInterface
{
    /**
     * {@inheritdoc}
     */
    public function getQtyToDiscountByRule($rule, $items)
    {
        return 1;
    }
}
