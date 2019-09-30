<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\ProductAttribute\SaveHandler;

use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class ProductAttributeList
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\ProductAttribute\SaveHandler
 */
class ProductAttributeList
{
    /**
     * Prepare list of attribute codes used in rule conditions
     *
     * @param RuleInterface $rule
     * @return array
     */
    public function prepare($rule)
    {
        $attributes = $this->retrieveAttributeCodes($rule->getCartConditions());
        return $attributes;
    }

    /**
     * Retrive attribute codes from serialized string
     *
     * @param string $serializedString
     * @return array
     */
    private function retrieveAttributeCodes($serializedString)
    {
        preg_match_all(
            '~"Aheadworks\\\\\\\\Afptc\\\\\\\\Model\\\\\\\\Rule' .
            '\\\\\\\\Condition\\\\\\\\Cart\\\\\\\\Product","attribute":"(.*?)"~',
            $serializedString,
            $matches
        );
        return array_values($matches[1]);
    }
}
