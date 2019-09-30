<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\Quote;

use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResource;
use Magento\Quote\Model\Quote\Config;
use Magento\Eav\Api\Data\AttributeInterface;

/**
 * Class ConfigProductAttributes
 *
 * @package Aheadworks\Afptc\Plugin\Model\Quote
 */
class ConfigProductAttributes
{
    /**
     * @var RuleResource
     */
    protected $ruleResource;

    /**
     * @param RuleResource $ruleResource
     */
    public function __construct(RuleResource $ruleResource)
    {
        $this->ruleResource = $ruleResource;
    }

    /**
     * Append rule product attribute keys to quote item collection
     *
     * @param Config $subject
     * @param array $attributeKeys
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetProductAttributes(Config $subject, array $attributeKeys)
    {
        $attributes = $this->ruleResource->getActiveAttributes();
        foreach ($attributes as $attribute) {
            if (!in_array($attribute[AttributeInterface::ATTRIBUTE_CODE], $attributeKeys)) {
                $attributeKeys[] = $attribute[AttributeInterface::ATTRIBUTE_CODE];
            }
        }
        return $attributeKeys;
    }
}
