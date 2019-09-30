<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount\Calculator;

use Aheadworks\Afptc\Model\Rule\Discount\Calculator\ByPercent\Items as ItemsCalculator;

/**
 * Class ByPercent
 *
 * @package Aheadworks\Afptc\Model\Rule\Discount\Calculator
 */
class ByPercent extends AbstractCalculator
{
    /**
     * @param ItemsCalculator $itemsCalculator
     */
    public function __construct(
        ItemsCalculator $itemsCalculator
    ) {
        parent::__construct($itemsCalculator);
    }
}
