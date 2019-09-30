<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Discount\Calculator;

use Aheadworks\Afptc\Model\Rule\Discount\Calculator\ByFixedPrice\Items as ItemsCalculator;

/**
 * Class ByFixedPrice
 * @package Aheadworks\Afptc\Model\Rule\Discount\Calculator
 */
class ByFixedPrice extends AbstractCalculator
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
