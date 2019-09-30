<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions;

use Aheadworks\Afptc\Model\Source\Rule\Scenario;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\BuyXGetY;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\Complete;

/**
 * Class TypeResolver
 *
 * @package Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions
 */
class TypeResolver
{
    /**
     * @var array
     */
    private $typeMapping;

    /**
     * @param array $typeMapping
     */
    public function __construct(array $typeMapping)
    {
        $this->typeMapping = $typeMapping;
    }

    /**
     * Return rule scenario by condition type
     *
     * @param string $conditionPrefix
     * @return array
     */
    public function resolve($conditionPrefix)
    {
        return $this->typeMapping[$conditionPrefix];
    }
}
